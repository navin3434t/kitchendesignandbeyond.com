# Zoo Theme Core Framework

## 1. Requirements

    1. PHP >= 5.3.2
    1. MySQL >= 5.5
    2. WordPress >= 4.3

## 2. Installation

Clone this repos, alter or modified anything outside the `lib` folder to meet your theme features and functionality.

### 2.1  Must-have folders and files.

### 2.1.0 `lib`

**:exclamation:DO NOT TOUCH ANYTHING INSIDE THIS FOLDER!**

Contain core features and functionality, everything inside this folder is well maintained and updated frequently. It means that any modification will be lost in a single commit.

**What're core features and functionality?**

#### Core features

Ahhh Uhhh! IDK. Add whatever feature your theme needed.

**YOU SHOULD NOT RE-REGISTER THOSE FEATURES!**

#### Hooks

1. [zoo_customize_before_register](https://github.com/cleversoft/clever-theme/blob/headerBuilder/core/customize/class-zoo-customizer.php#L1066):

You may use this hook to add extra panels, sections, fields, settings into Zoo Theme Core customizer.

Example

    /**
     * Register custom theme mods from a theme
     *
     * @param  object  $zoo_customizer  \Zoo_Customizer
     * @param  object  $wp_customize    \WP_Customize_Manager
     * @param  mixed   $mods            Theme mods - value of `get_theme_mods()`
     */
    function register_theme_mods($zoo_customizer, $wp_customize, $mods)
    {
      $zoo_customizer->add_section( 'custom', array(
          'title' => esc_html__( 'Custom CSS/JS', 'fona' )
      ) );
      $zoo_customizer->add_field( 'zoo_customizer', array(
          'type'     => 'textarea',
          'settings' => 'zoo_custom_css',
          'label'    => esc_html__( 'Custom CSS', 'fona' ),
          'section'  => 'custom',
          'default'  => ''
      ) );
      $zoo_customizer->add_field( 'zoo_customizer', array(
          'type'     => 'textarea',
          'settings' => 'zoo_custom_js',
          'label'    => esc_html__( 'Custom JS', 'fona' ),
          'section'  => 'custom',
          'default'  => ''
      ) );
    }
    add_action('zoo_before_customize_register', 'register_theme_mods', 10, 3);

2. [zoo_add_customize_builder_elements](https://github.com/cleversoft/clever-theme/blob/headerBuilder/core/customize/builder/class-zoo-customize-builder.php#L90):

You may use this hook to add extra builder elements to the core customize builder.

Example

    <?php
    /**
     * Html6_Builder_Element
     */
    final class Html6_Builder_Element extends Zoo_Customize_Builder_Element
    {
        /**
         * Constructor
         */
        public function __construct()
        {
            $this->id      = 'html6';
            $this->title   = esc_html__('HTML 6', 'text-domain');
            $this->width   = 4;
            $this->section = 'header_html6';
            $this->panel   = 'header_settings';
        }

        /**
         * Register Builder item
         * @return array
         */
        public function get_builder_configs()
        {
            return array(
                'name'    => $this->title,
                'id'      => $this->id,
                'col'     => 0,
                'width'   => $this->width,
                'section' => $this->section // Customizer section to focus when click settings
            );
        }

        /**
         * Optional, Register customize section and panel.
         *
         * @return array
         */
        public function get_customize_configs(WP_Customize_Manager $wp_customize = null)
        {
            // Register customize configs here...
        }

        /**
         * Optional. Render item content
         */
        public function render()
        {
            // Render element here.
        }
    }

    /**
     * Register custom html6 builder block
     *
     * @param  object  $builder  \Zoo_Customize_Builder
     */
    function theme_prefix_add_html6_builder_element($builder)
    {
        // Suppose that you put Html6_Builder_Element class somewhere inside `inc/customize`
        require ZOO_THEME_DIR . 'inc/customize/somewhere/html6.php';

        $builder->add_element('header', new Html6_Builder_Element());
    }
    add_action('zoo_add_customize_builder_elements', 'theme_prefix_add_html6_builder_element');

!NOTE: All custom builder elements must extends the `Zoo_Customize_Builder_Element` class or it won't work.

### 2.1.1 `inc/plugins`

This folder contains all pre-packaged plugins zip files which will be installed while installing theme via theme setup wizard.

**:pizza: How it works?**

Zoo Theme Core uses [TGM Plugin Activation](http://tgmpluginactivation.com) to include and install theme required plugins. So, you know how it work, right?

1. Put all pre-packaged plugins' zip files into the `inc/plugins` folder.

2. Register recommended and required plugins with TGMPA:

Example:

    function zoo_register_required_plugins()
    {
        $plugins = array(
            array(
                'name'      => 'WPBakery Visual Composer',
                'slug'      => 'js_composer',
                'required'  => true,
                'source'    => 'js_composer.zip',
                'version'   => '5.0.1'
            ),
            array(
                'name'     => 'Contact Form 7',
                'slug'     => 'contact-form-7',
                'required' => true,
                'version'  => '4.6'
            )
        );

        $config = array(
            'default_path' => ZOO_THEME_DIR . 'inc/plugins/',
        );

        tgmpa($plugins, $config);
    }
    add_action( 'tgmpa_register', 'zoo_register_required_plugins', 10, 0 );

That's all! There're four pre-registered plugins: WPBakery Visual Composer, Contact Form 7, Revolution Slider and Clever Visual Composer Addon. You should not re-register those plugins.

### 2.1.2 `inc/sample-data`

This folder contains all starter content for theme. All sample content inside this folder will be imported automatically while installing theme via theme setup wizard.

**:pizza: How it works?**

1. `base` - contain base content files:

    - `content.xml` - base starter content.
    - 'customizer.dat' - base theme customize options.
    - `slider.zip` - base revolution slider.
    - `widgets.wie` - base widgets.

2.  `default` - default homepage content which will be installed by default if user doesn't select a custom homepage.

3. Others - contain starter content of other homepages.

**::exclamation: ZOO THEME CORE USES FILES' NAME TO IDENTIFY WHICH FILE SHOULD BE IMPORT SO YOU SHOULD NOT CHANGE FILES' NAME**

### 2.1.3 `inc/init.php`

Since the `functions.php` will be used as bootstrap file of theme core, theme must load extra features and functionality from the `inc/init.php` file. `functions.php` will load the `inc/init.php` to initialize your theme.

**:exclamation: YOU MUST NOT MODIFY FUNCTIONS.PHP OR DELETE INIT.PHP FILE!**

### 3 Fix issue sample data can't import after update WooCommerce to 3.6.x
**:exclamation: Apply for site demo have been install WooCommerce 3.6.x when waiting solution from WC**
- Download [this file](https://github.com/woocommerce/woocommerce/blob/d2d342f30eec99b606e81b02ef678ba2e7737cc0/includes/admin/class-wc-admin.php)
- Use FTP and go this folder on demo site: 'site_name/plugins/woocommerce/includes/admin/'
- Override file `class-wc-admin.php` by file downloaded before.
- Export Sample data like normal.
