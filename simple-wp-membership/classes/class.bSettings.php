<?php

class BSettings {

    private static $_this;
    private $settings;
    public $current_tab;

    private function __construct() {
        if (is_admin()) {
            $this->current_tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : 1;
            add_action('swpm-draw-tab', array(&$this, 'draw_tabs'));
            $method = 'tab_' . $this->current_tab;
            if (method_exists($this, $method))
                $this->$method();
        }
        $this->settings = (array) get_option('swpm-settings');
    }

    private function tab_1() {
        register_setting('swpm-settings-tab-1', 'swpm-settings', array(&$this, 'sanitize_tab_1'));
        add_settings_section('general-settings', 'General Settings', array(&$this, 'general_settings_callback'), 'simple_wp_membership_settings');
        add_settings_field('enable-free-membership', 'Enable Free Membership', array(&$this, 'checkbox_callback'), 'simple_wp_membership_settings', 'general-settings', array('item' => 'enable-free-membership'));
        add_settings_field('free-membership-id', 'Free Membership Level ID', array(&$this, 'textfield_small_callback'), 'simple_wp_membership_settings', 'general-settings', array('item' => 'free-membership-id'));
        add_settings_section('pages-settings', 'Pages Settings', array(&$this, 'pages_settings_callback'), 'simple_wp_membership_settings');
        add_settings_field('login-page-url', 'Login Page URL', array(&$this, 'textfield_long_callback'), 'simple_wp_membership_settings', 'pages-settings', array('item' => 'login-page-url'));
        add_settings_field('registration-page-url', 'Registration Page URL', array(&$this, 'textfield_long_callback'), 'simple_wp_membership_settings', 'pages-settings', array('item' => 'registration-page-url'));
        add_settings_field('join-us-page-url', 'Join Us Page URL', array(&$this, 'textfield_long_callback'), 'simple_wp_membership_settings', 'pages-settings', array('item' => 'join-us-page-url'));
        add_settings_field('profile-page-url', 'Edit Profile Page URL', array(&$this, 'textfield_long_callback'), 'simple_wp_membership_settings', 'pages-settings', array('item' => 'profile-page-url'));
        add_settings_field('reset-page-url', 'Password Reset Page URL', array(&$this, 'textfield_long_callback'), 'simple_wp_membership_settings', 'pages-settings', array('item' => 'reset-page-url'));        
    }

    private function tab_2() {
        //register_setting( 'swpm-settings-tab-2', 'swpm-settings' , array(&$this, 'sanitize_tab_2'));			
        //add_settings_section('paypal-settings', 'PayPal Settings', array(&$this,'pp_payment_settings_callback'), 'simple_wp_membership_settings');
        //add_settings_field( 'paypal-email', 'PayPal Email', array(&$this, 'textfield_callback'), 'simple_wp_membership_settings', 'paypal-settings' ,array('item'=>'paypal-email'));				
    }

    private function tab_3() {
        register_setting('swpm-settings-tab-3', 'swpm-settings', array(&$this, 'sanitize_tab_3'));

        add_settings_section('reg-prompt-email-settings', 'Email Settings (Prompt to Complete Registration )', array(&$this, 'reg_prompt_email_settings_callback'), 'simple_wp_membership_settings');
        add_settings_field('reg-prompt-complete-mail-subject', 'Email Subject', array(&$this, 'textfield_callback'), 'simple_wp_membership_settings', 'reg-prompt-email-settings', array('item' => 'reg-prompt-complete-mail-subject'));
        add_settings_field('reg-prompt-complete-mail-body', 'Email Body', array(&$this, 'textarea_callback'), 'simple_wp_membership_settings', 'reg-prompt-email-settings', array('item' => 'reg-prompt-complete-mail-body'));

        add_settings_section('reg-email-settings', 'Email Settings (Registration Complete)', array(&$this, 'reg_email_settings_callback'), 'simple_wp_membership_settings');
        add_settings_field('reg-complete-mail-subject', 'Email Subject', array(&$this, 'textfield_callback'), 'simple_wp_membership_settings', 'reg-email-settings', array('item' => 'reg-complete-mail-subject'));
        add_settings_field('reg-complete-mail-body', 'Email Body', array(&$this, 'textarea_callback'), 'simple_wp_membership_settings', 'reg-email-settings', array('item' => 'reg-complete-mail-body'));
        add_settings_field('enable-admin-notification-after-reg', 'Send Notification To Admin', array(&$this, 'checkbox_callback'), 'simple_wp_membership_settings', 'reg-email-settings', array('item' => 'enable-admin-notification-after-reg'));
        add_settings_field('enable-notification-after-manual-user-add', 'Send Email to Member When Added via Admin Dashboard', array(&$this, 'checkbox_callback'), 'simple_wp_membership_settings', 'reg-email-settings', array('item' => 'enable-notification-after-manual-user-add'));

        add_settings_section('upgrade-email-settings', ' Email Settings (Account Upgrade Notification)', array(&$this, 'upgrade_email_settings_callback'), 'simple_wp_membership_settings');
        add_settings_field('upgrade-complete-mail-subject', 'Email Subject', array(&$this, 'textfield_callback'), 'simple_wp_membership_settings', 'upgrade-email-settings', array('item' => 'upgrade-complete-mail-subject'));
        add_settings_field('upgrade-complete-mail-body', 'Email Body', array(&$this, 'textarea_callback'), 'simple_wp_membership_settings', 'upgrade-email-settings', array('item' => 'upgrade-complete-mail-body'));
    }

    public static function get_instance() {
        self::$_this = empty(self::$_this) ? new BSettings() : self::$_this;
        return self::$_this;
    }

    public function checkbox_callback($args) {
        $item = $args['item'];
        $is = esc_attr($this->get_value($item));
        echo "<input type='checkbox' $is name='swpm-settings[" . $item . "]' value=\"checked='checked'\" />";
    }

    public function textarea_callback($args) {
        $item = $args['item'];
        $text = esc_attr($this->get_value($item));
        echo "<textarea name='swpm-settings[" . $item . "]'  rows='6' cols='60' >" . $text . "</textarea>";
    }

    public function textfield_small_callback($args) {
        $item = $args['item'];
        $text = esc_attr($this->get_value($item));
        echo "<input type='text' name='swpm-settings[" . $item . "]'  size='5' value='" . $text . "' />";
    }
    
    public function textfield_callback($args) {
        $item = $args['item'];
        $text = esc_attr($this->get_value($item));
        echo "<input type='text' name='swpm-settings[" . $item . "]'  size='50' value='" . $text . "' />";
    }
    
    public function textfield_long_callback($args) {
        $item = $args['item'];
        $text = esc_attr($this->get_value($item));
        echo "<input type='text' name='swpm-settings[" . $item . "]'  size='100' value='" . $text . "' />";
    }    

    public function general_settings_callback() {
        echo "<p>General Setting are added here.</p>";
    }

    public function reg_email_settings_callback() {
        echo "<p>This email will be sent to your users when they complete the registration and become a member.</p>";
    }

    public function upgrade_email_settings_callback() {
        echo "<p>This email will be sent to your users after account upgrade.</p>";
    }

    public function reg_prompt_email_settings_callback() {
        echo "<p>This email will be sent to prompt user to complete registration.</p>";
    }

    public function pages_settings_callback() {
        echo '<p>URL related settings goes here.<p>';
    }

    public function sanitize_tab_1($input) {
        if (empty($this->settings))
            $this->settings = (array) get_option('swpm-settings');
        $output = $this->settings;
        //general settings block   
        if (isset($input['enable-free-membership']))
            $output['enable-free-membership'] = esc_url($input['enable-free-membership']);
        else
            $output['enable-free-membership'] = "";
        $output['free-membership-id'] = ($input['free-membership-id'] != 1) ? absint($input['free-membership-id']) : '';
        $output['login-page-url'] = esc_url($input['login-page-url']);
        $output['registration-page-url'] = esc_url($input['registration-page-url']);
        $output['profile-page-url'] = esc_url($input['profile-page-url']);
        $output['reset-page-url'] = esc_url($input['reset-page-url']);
        $output['join-us-page-url'] = esc_url($input['join-us-page-url']);        
        return $output;
    }

    public function sanitize_tab_3($input) {
        $output['reg-complete-mail-subject'] = sanitize_text_field($input['reg-complete-mail-subject']);
        $output['reg-complete-mail-body'] = wp_kses_data(force_balance_tags($input['reg-complete-mail-body']));

        $output['upgrade-complete-mail-subject'] = sanitize_text_field($input['upgrade-complete-mail-subject']);
        $output['upgrade-complete-mail-body'] = wp_kses_data(force_balance_tags($input['upgrade-complete-mail-body']));

        $output['reg-prompt-complete-mail-subject'] = sanitize_text_field($input['reg-prompt-complete-mail-subject']);
        $output['reg-prompt-complete-mail-body'] = wp_kses_data(force_balance_tags($input['reg-prompt-complete-mail-body']));

        if (isset($input['enable-admin-notification-after-reg']))
            $output['enable-admin-notification-after-reg'] = esc_html($input['enable-admin-notification-after-reg']);
        else
            $output['enable-admin-notification-after-reg'] = "";
        if (isset($input['enable-notification-after-manual-user-add']))
            $output['enable-notification-after-manual-user-add'] = esc_html($input['enable-notification-after-manual-user-add']);
        else
            $output['enable-notification-after-manual-user-add'] = "";
        return $output;
    }

    public function get_value($key, $default = "") {
        if (isset($this->settings[$key]))
            return $this->settings[$key];
        return $default;
    }

    public function set_value($key, $value) {
        $this->settings[$key] = $value;
        return $this;
    }

    public function save() {
        update_option('swpm-settings', $this->settings);
    }

    public function draw_tabs() {
        $current = $this->current_tab;
        ?>
        <h3 class="nav-tab-wrapper"> 
            <a class="nav-tab <?php echo ($current == 1) ? 'nav-tab-active' : ''; ?>" href="admin.php?page=simple_wp_membership_settings">General Settings</a>
            <a class="nav-tab <?php echo ($current == 2) ? 'nav-tab-active' : ''; ?>" href="admin.php?page=simple_wp_membership_settings&tab=2">Payment Settings</a>
            <a class="nav-tab <?php echo ($current == 3) ? 'nav-tab-active' : ''; ?>" href="admin.php?page=simple_wp_membership_settings&tab=3">Email Settings</a>
        </h3>
        <?php
    }

    public function get_login_link() {
        $login  = $this->get_value('login-page-url');
        $joinus = $this->get_value('join-us-page-url');
        if (empty ($login) || empty($joinus)){
            return '<span style="color:red;">Simple Membership is not configured correctly.'
            . 'Please contact <a href="mailto:' . get_option('admin_email'). '">Admin</a>';  
        }
        return 'Please <a href="' . $login . '">Login</a>. Not a Member? <a href="' . $joinus . '">Join Us</a>';
    }

}