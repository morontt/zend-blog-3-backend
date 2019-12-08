'use strict';

module.exports = function (grunt) {
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            css_login: {
                options: {
                    stripBanners: {
                        block: true
                    },
                    banner: '/*! <%= pkg.name %> --- <%= grunt.template.today("dd mmm yyyy HH:MM:ss") %> */\n'
                },
                src: [
                    'node_modules/bootstrap/dist/css/bootstrap.css',
                    'web/bundles/mttblog/css/main.css'
                ],
                dest: 'web/dist/<%= pkg.name %>_login.css'
            },
            css_jquery_ui: {
                options: {
                    stripBanners: {
                        block: true
                    },
                    banner: '/*! <%= pkg.name %> --- <%= grunt.template.today("dd mmm yyyy HH:MM:ss") %> */\n'
                },
                src: [
                    'node_modules/jquery-ui/themes/base/core.css',
                    'node_modules/jquery-ui/themes/base/accordion.css',
                    'node_modules/jquery-ui/themes/base/autocomplete.css',
                    'node_modules/jquery-ui/themes/base/button.css',
                    'node_modules/jquery-ui/themes/base/datepicker.css',
                    'node_modules/jquery-ui/themes/base/dialog.css',
                    'node_modules/jquery-ui/themes/base/draggable.css',
                    'node_modules/jquery-ui/themes/base/menu.css',
                    'node_modules/jquery-ui/themes/base/progressbar.css',
                    'node_modules/jquery-ui/themes/base/resizable.css',
                    'node_modules/jquery-ui/themes/base/selectable.css',
                    'node_modules/jquery-ui/themes/base/selectmenu.css',
                    'node_modules/jquery-ui/themes/base/sortable.css',
                    'node_modules/jquery-ui/themes/base/slider.css',
                    'node_modules/jquery-ui/themes/base/spinner.css',
                    'node_modules/jquery-ui/themes/base/tabs.css',
                    'node_modules/jquery-ui/themes/base/tooltip.css',
                    'node_modules/jquery-ui/themes/base/theme.css'
                ],
                dest: 'web/dist/<%= pkg.name %>_jq_ui.css'
            },
            css_preview: {
                options: {
                    stripBanners: {
                        block: true
                    },
                    banner: '/*! <%= pkg.name %> --- <%= grunt.template.today("dd mmm yyyy HH:MM:ss") %> */\n'
                },
                src: [
                    'web/dist/<%= pkg.name %>_jq_ui.css',
                    'node_modules/bootstrap/dist/css/bootstrap.css',
                    'web/bundles/mttblog/css/blog.css'
                ],
                dest: 'web/dist/<%= pkg.name %>_preview.css'
            },
            css_main: {
                options: {
                    stripBanners: {
                        block: true
                    },
                    banner: '/*! <%= pkg.name %> --- <%= grunt.template.today("dd mmm yyyy HH:MM:ss") %> */\n'
                },
                src: [
                    'web/dist/<%= pkg.name %>_jq_ui.css',
                    'node_modules/bootstrap/dist/css/bootstrap.css',
                    'web/bundles/mttblog/css/main.css'
                ],
                dest: 'web/dist/<%= pkg.name %>_main.css'
            },
            js: {
                options: {
                    stripBanners: {
                        block: true
                    },
                    banner: '/*! <%= pkg.name %> --- <%= grunt.template.today("dd mmm yyyy HH:MM:ss") %> */\n'
                },
                src: [
                    'web/spa/assets/vendor.js',
                    'web/bundles/mttblog/components/bootstrap/dist/js/bootstrap.js',
                    'web/bundles/mttblog/components/moment/moment.js',
                    'web/bundles/mttblog/components/moment/locale/ru.js',
                    'web/bundles/mttblog/components/jquery-ui/ui/core.js',
                    'web/bundles/mttblog/components/jquery-ui/ui/widget.js',
                    'web/bundles/mttblog/components/jquery-ui/ui/position.js',
                    'web/bundles/mttblog/components/jquery-ui/ui/autocomplete.js',
                    'web/bundles/mttblog/components/jquery-ui/ui/menu.js',
                    'web/bundles/fosjsrouting/js/router.js',
                    'web/spa/assets/mtt-blog.js'
                ],
                dest: 'web/dist/<%= pkg.name %>.js'
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    'web/dist/<%= pkg.name %>_login.min.css': ['<%= concat.css_login.dest %>'],
                    'web/dist/<%= pkg.name %>_preview.min.css': ['<%= concat.css_preview.dest %>'],
                    'web/dist/<%= pkg.name %>_main.min.css': ['<%= concat.css_main.dest %>']
                }
            }
        },
        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> v<%= pkg.version %> ' +
                    '--- <%= grunt.template.today("dd mmm yyyy HH:MM:ss") %> */\n'
            },
            dist: {
                files: {
                    'web/dist/<%= pkg.name %>.min.js': ['<%= concat.js.dest %>']
                }
            }
        }
    });

    grunt.registerTask('build', ['concat', 'cssmin', 'uglify']);
    grunt.registerTask('default', ['build']);
};
