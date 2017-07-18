<?php
/**
 * Class PhpUnitDemoPluginTest
 *
 * @package Phpunit_Demo_Plugin
 */

/**
 * Test case for the PHPUnit Demo Plugin
 */
class PhpUnitDemoPluginTest extends WP_UnitTestCase {

    public function setUp() {
            parent::setUp();  

    }    

    public function tearDown() {
            parent::tearDown();  

    }    

    /**
     * Test add_user_meta for new user with a non-editor role
     */    
    function test_nds_custom_meta_add_for_non_editors() {
            $factory_user_id = $this->factory->user->create( array('role' => 'author') );
            $get_user_meta = get_user_meta($factory_user_id, 'preferred_browser', true );
            //an empty string will be returned as user was not an editor
            
            $this->assertEquals($get_user_meta, '');
            
    }
    
    /**
     * Test add_user_meta for new user with an editor role
     */        
    function test_nds_custom_meta_add_for_editors() {
                        
            $factory_editor_id = $this->factory->user->create( array('role' => 'editor') );
            $get_editor_user_meta = get_user_meta($factory_editor_id, 'preferred_browser', true );            
            
            //for users with editor role meta key for preferred browser cannot be empty            
            $this->assertEquals($get_editor_user_meta, 'chrome');
    }
    
    /**
     * Test callback priority
     */        
    function test_callback_for_user_register() {
            $this->assertGreaterThan( 
                    10, 
                    has_action( 'user_register', 'nds_custom_meta_add' ) 
                    );
    }
    
    /**
     * Test meta value update
     */      
    function test_nds_custom_meta_update_for_editors() {
            $factory_editor_id = $this->factory->user->create( array('role' => 'editor') );                                              
            $get_editor_user_meta = get_user_meta($factory_editor_id, 'preferred_browser', true );
            
            //for new users with editor role the default meta value for preferred_browser is chrome
            $this->assertEquals($get_editor_user_meta, 'chrome');
            
            //spoof $_POST
            $_POST['preferred_browser'] = 'opera';
            
            //make sure we are admin
            wp_set_current_user(1);
            
            //call the update method
            nds_update_usermeta_field_browser($factory_editor_id);
            $get_editor_user_meta = get_user_meta($factory_editor_id, 'preferred_browser', true );
            
            //test the meta value was updated correctly
            $this->assertEquals($get_editor_user_meta, 'opera');
    }
        
}
