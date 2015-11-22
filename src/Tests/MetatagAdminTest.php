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
  public static $modules = array('metatag');

  /**
   * Tests the interface to manage metatag defaults.
   */
  function testDefaults() {
    $metatag_defaults = \Drupal::config('metatag.global');

    // Initiate session with a user who can manage metatags.
    $permissions = array('administer site configuration', 'administer metatags');
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

    // Update the Global defaults.
    $values = array(
      'title' => 'Test title',
      'description' => 'Test description',
      'abstract' => 'Test abstract',
      'keywords' => 'Test keywords',
    );
    $this->drupalPostForm('admin/structure/metatag_defaults/global', $values, 'Save');
    $this->assertText('Saved the Global Metatag defaults.');

    // Check that the new values are found in the response.
    $this->drupalGet('<front>');
    foreach ($values as $metatag => $value) {
      $this->assertRaw($value, t('Updated metatag @tag was found in the HEAD section of the page.', array('@tag' => $metatag)));
    }
  }

}
