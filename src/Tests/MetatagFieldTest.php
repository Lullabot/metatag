<?php
/**
 * @file
 * Contains \Drupal\metatag\Tests\MetatagFieldTest.
 */

namespace Drupal\metatag\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Ensures that the Metatag field works correctly.
 *
 * @group Metatag
 */
class MetatagFieldTest extends WebTestBase {

  /**
   * Profile to use.
   */
  protected $profile = 'testing';

  /**
   * Admin user
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $adminUser;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'field_ui',
    'metatag',
    'entity_test',
  ];

  /**
   * Permissions to grant admin user.
   *
   * @var array
   */
  protected $permissions = [
    'access administration pages',
    'view test entity',
    'administer entity_test fields',
    'administer entity_test content',
    'administer meta tags',
  ];

  /**
   * Sets the test up.
   */
  protected function setUp() {
    parent::setUp();
    $this->adminUser = $this->drupalCreateUser($this->permissions);
    $this->drupalLogin($this->adminUser);
    // Add a new metatag field.
    $this->drupalGet('entity_test/structure/entity_test/fields/add-field');
    $edit = [
      'label' => 'Metatag',
      'field_name' => 'metatag',
      'new_storage_type' => 'metatag',
    ];
    $this->drupalPostForm(NULL, $edit, t('Save and continue'));
    $this->drupalPostForm(NULL, [], t('Save field settings'));
  }

  /**
   * Tests adding and editing values using metatag.
   */
  public function testMetatag() {
    $edit = [
      'default_value_input[field_metatag][0][basic][keywords]' => 'Purple monkey dishwasher',
    ];
    $this->drupalPostForm('entity_test/structure/entity_test/fields/entity_test.entity_test.field_metatag', $edit, t('Save settings'));
    $this->assertRaw(t('Saved %name configuration', ['%name' => 'Metatag']));
    $this->container->get('entity.manager')->clearCachedFieldDefinitions();

    // Test the fields values/widget.
    $this->drupalGet('entity_test/add');
    $this->assertFieldByName('field_metatag[0][basic][keywords]', 'Purple monkey dishwasher', 'Found metatag field metatag');

    // Submit with no metatags, should use defaults.
    $default_entity = $this->doTestDefaultMetatags();

    // Submit with specific metatags.
    $this->drupalGet('entity_test/add');
    $this->doTestOverriddenMetatags();

    // Change defaults.
    $edit = [
      'default_value_input[field_metatag][0][basic][keywords]' => "Green monkey dishwasher",
    ];
    $this->drupalPostForm('entity_test/structure/entity_test/fields/entity_test.entity_test.field_metatag', $edit, t('Save settings'));

    // Test new defaults applied.
    $this->drupalGet('entity_test/' . $default_entity->id());
    $elements = $this->cssSelect('meta[name=keywords]');
    $this->assertTrue(count($elements) === 1, 'Found keywords metatag from defaults');
    $this->assertEqual((string) $elements[0]['content'], "Green monkey dishwasher", 'Default keywords applied');

    // Verify that the URLs aren't being broken.
    $this->doTestUrlMetatags();
  }

  /**
   * Tests inheritance in default metatags.
   *
   * When the bundle does not define a default value, global or entity defaults
   * are used instead.
   */
  protected function testDefaultInheritance() {
    // First we set global defaults.
    $global_values = array(
      'description' => 'Global description',
    );
    $this->drupalPostForm('admin/structure/metatag_defaults/global', $global_values, 'Save');
    $this->assertText('Saved the Global Metatag defaults.');

    // Then we remove field defaults.
    $field_values = array(
      'default_value_input[field_metatag][0][basic][description]' => '',
    );
    $this->drupalPostForm('entity_test/structure/entity_test/fields/entity_test.entity_test.field_metatag', $field_values, t('Save settings'));
    $this->assertText('Saved Metatag configuration.');

    // Now when we create an entity, global defaults are used to fill the form fields.
    $this->drupalGet('entity_test/add');
    $this->assertFieldByName('field_metatag[0][basic][description]', $global_values['description'], t('Description field has the global default as the field default does not define it.'));
  }

  /**
   * Tests default metatags are applied.
   */
  protected function doTestDefaultMetatags() {
    $edit = [
      'name[0][value]' => 'Barfoo',
      'user_id[0][target_id]' => 'foo (' . $this->adminUser->id() . ')',
    ];

    $this->drupalPostForm(NULL, $edit, t('Save'));
    $entities = entity_load_multiple_by_properties('entity_test', [
      'name' => 'Barfoo',
    ]);
    $this->assertEqual(1, count($entities), 'Entity was saved');
    $entity = reset($entities);
    $this->drupalGet('entity_test/' . $entity->id());
    $elements = $this->cssSelect('meta[name=keywords]');
    $this->assertTrue(count($elements) === 1, 'Found keywords metatag from defaults');
    $this->assertEqual((string) $elements[0]['content'], 'Purple monkey dishwasher', 'Default keywords applied');
    return $entity;
  }

  /**
   * Tests overridden metatags are applied.
   */
  protected function doTestOverriddenMetatags() {
    $edit = [
      'name[0][value]' => 'Wizwang',
      'user_id[0][target_id]' => 'foo (' . $this->adminUser->id() . ')',
      'field_metatag[0][basic][keywords]' => 'She was like yes way and I was like no way and she was like shut-up',
    ];

    $this->drupalPostForm(NULL, $edit, t('Save'));
    $entities = entity_load_multiple_by_properties('entity_test', [
      'name' => 'Wizwang',
    ]);
    $this->assertEqual(1, count($entities), 'Entity was saved');
    $entity = reset($entities);
    $this->drupalGet('entity_test/' . $entity->id());
    $elements = $this->cssSelect('meta[name=keywords]');
    $this->assertTrue(count($elements) === 1, 'Found keywords metatag from defaults');
    $this->assertEqual((string) $elements[0]['content'], 'She was like yes way and I was like no way and she was like shut-up', 'Overridden keywords applied');
    return $entity;
  }

  /**
   * Tests metatags with urls work.
   */
  protected function doTestUrlMetatags() {
    $this->drupalGet('entity_test/add');
    $edit = [
      'name[0][value]' => 'UrlTags',
      'user_id[0][target_id]' => 'foo (' . $this->adminUser->id() . ')',
      'field_metatag[0][open_graph][og_url]' => 'http://example.com/foo.html',
    ];

    $this->drupalPostForm(NULL, $edit, t('Save'));
    $entities = entity_load_multiple_by_properties('entity_test', [
      'name' => 'UrlTags',
    ]);
    $this->assertEqual(1, count($entities), 'Entity was saved');
    $entity = reset($entities);
    $this->drupalGet('entity_test/' . $entity->id());
    $elements = $this->cssSelect("meta[property='og:url']");
    $this->assertTrue(count($elements) === 1, 'Found keywords metatag from defaults');
    $this->assertEqual((string) $elements[0]['content'], 'http://example.com/foo.html');
    return $entity;
  }

}
