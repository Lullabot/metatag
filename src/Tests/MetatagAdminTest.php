<?php
/**
 * @file
 * Contains \Drupal\metatag\Tests\MetatagAdminTest.
 */

namespace Drupal\metatag\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Tests the Metatag administration.
 *
 * @group Metatag
 */
class MetatagAdminTest extends WebTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = array('metatag', 'node');

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Create Basic page and Article node types.
    if ($this->profile != 'standard') {
      $this->drupalCreateContentType(array(
        'type' => 'page',
        'name' => 'Basic page',
        'display_submitted' => FALSE,
      ));
      $this->drupalCreateContentType(array('type' => 'article', 'name' => 'Article'));
    }
  }

  /**
   * Tests the interface to manage metatag defaults.
   */
  function testDefaults() {
    // Save the default title to test the Revert operation at the end.
    $metatag_defaults = \Drupal::config('metatag.metatag_defaults.global');
    $default_title = $metatag_defaults->get('tags')['title'];

    // Initiate session with a user who can manage metatags.
    $permissions = array('administer site configuration', 'administer meta tags');
    $account = $this->drupalCreateUser($permissions);
    $this->drupalLogin($account);

    // Check that the user can see the list of metatag defaults.
    $this->drupalGet('admin/structure/metatag_defaults');
    $this->assertResponse(200);

    // Check that the Global defaults were created.
    $this->assertLinkByHref('/admin/structure/metatag_defaults/global', 0, t('Global defaults were created on installation.'));

    // Check that the module defaults were injected into the Global config entity.
    $this->drupalGet('admin/structure/metatag_defaults/global');
    $this->assertFieldById('edit-title', $metatag_defaults->get('title'), t('Metatag defaults were injected into the Global configuration entity.'));

    // Update the Global defaults and test them.
    $values = array(
      'title' => 'Test title',
      'description' => 'Test description',
    );
    $this->drupalPostForm('admin/structure/metatag_defaults/global', $values, 'Save');
    $this->assertText('Saved the Global Metatag defaults.');
    $this->drupalGet('hit-a-404');
    foreach ($values as $metatag => $value) {
      $this->assertRaw($value, t('Updated metatag @tag was found in the HEAD section of the page.', array('@tag' => $metatag)));
    }

    // Check that tokens are processed.
    $values = array(
      'title' => '[site:name] | Test title',
      'description' => '[site:name] | Test description',
    );
    $this->drupalPostForm('admin/structure/metatag_defaults/global', $values, 'Save');
    $this->assertText('Saved the Global Metatag defaults.');
    drupal_flush_all_caches();
    $this->drupalGet('hit-a-404');
    foreach ($values as $metatag => $value) {
      $processed_value = \Drupal::token()->replace($value);
      $this->assertRaw($processed_value, t('Processed token for metatag @tag was found in the HEAD section of the page.', array('@tag' => $metatag)));
    }

    // Test the Robots plugin.
    $robots_values = array('index', 'follow', 'noydir');
    $form_values = array();
    foreach ($robots_values as $value) {
      $values['robots[' . $value . ']'] = TRUE;
    }
    $this->drupalPostForm('admin/structure/metatag_defaults/global', $values, 'Save');
    $this->assertText('Saved the Global Metatag defaults.');
    drupal_flush_all_caches();
    $this->drupalGet('hit-a-404');
    $robots_value = implode(', ', $robots_values);
    $this->assertRaw($robots_value, t('Robots metatag has the expected values.'));

    // Test reverting global configuration to its defaults.
    $this->drupalPostForm('admin/structure/metatag_defaults/global/revert', array(), 'Revert');
    $this->assertText('Reverted Global defaults.');
    $this->assertText($default_title, 'Global title was reverted to its default value.');

    $this->drupalLogout();
  }


  /**
   * Tests entity and bundle overrides.
   */
  function testOverrides() {
    // Initiate session with a user who can manage metatags.
    $permissions = array('administer site configuration', 'administer meta tags', 'access content', 'create article content', 'administer nodes', 'create article content', 'create page content');
    $account = $this->drupalCreateUser($permissions);
    $this->drupalLogin($account);

    // Update the Metatag Node defaults.
    $values = array(
      'title' => 'Test title for a node.',
      'description' => 'Test description for a node.',
    );
    $this->drupalPostForm('admin/structure/metatag_defaults/node', $values, 'Save');
    $this->assertText('Saved the Content Metatag defaults.');

    // Create a test node.
    $node = $this->drupalCreateNode(array(
      'title' => t('Hello, world!'),
      'type' => 'article',
    ));

    // Check that the new values are found in the response.
    $this->drupalGet('node/' . $node->id());
    foreach ($values as $metatag => $value) {
      $this->assertRaw($value, t('Node metatag @tag overrides Global defaults.', array('@tag' => $metatag)));
    }

    /**
     * Check that when the node defaults don't define a metatag, the Global one is used.
     */
    // First unset node defaults.
    $values = array(
      'title' => '',
      'description' => '',
    );
    $this->drupalPostForm('admin/structure/metatag_defaults/node', $values, 'Save');
    $this->assertText('Saved the Content Metatag defaults.');

    // Then, set global ones.
    $values = array(
      'title' => 'Global title',
      'description' => 'Global description',
    );
    $this->drupalPostForm('admin/structure/metatag_defaults/global', $values, 'Save');
    $this->assertText('Saved the Global Metatag defaults.');

    // @TODO  BookTest.php resets the cache of a single node, which is way more
    // performant than creating a node for every set of assertions.
    // @see BookTest::testDelete().
    $node = $this->drupalCreateNode(array(
      'title' => t('Hello, world!'),
      'type' => 'article',
    ));
    $this->drupalGet('node/' . $node->id());
    foreach ($values as $metatag => $value) {
      $this->assertRaw($value, t('Found global @tag tag as Node does not set it.', array('@tag' => $metatag)));
    }
  }

}
