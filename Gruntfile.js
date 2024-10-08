'use strict';

module.exports = function (grunt) {
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        banner_format: '/* <%= pkg.name %> v<%= pkg.version %> --- <%= grunt.template.today("dd mmm yyyy HH:MM:ss o") %> */\n',
        concat: {
            css_login: {
                options: {
                    stripBanners: {
                        block: true
                    },
                    banner: '<%= banner_format %>'
                },
                src: [
                    'bower_components/bootstrap/dist/css/bootstrap.css',
                    'web/css/main.css'
                ],
                dest: 'web/dist/<%= pkg.name %>_login.css'
            },
            css_jquery_ui: {
                options: {
                    stripBanners: {
                        block: true
                    },
                    banner: '<%= banner_format %>'
                },
                src: [
                    'bower_components/jquery-ui/themes/base/core.css',
                    'bower_components/jquery-ui/themes/base/accordion.css',
                    'bower_components/jquery-ui/themes/base/autocomplete.css',
                    'bower_components/jquery-ui/themes/base/button.css',
                    'bower_components/jquery-ui/themes/base/datepicker.css',
                    'bower_components/jquery-ui/themes/base/dialog.css',
                    'bower_components/jquery-ui/themes/base/draggable.css',
                    'bower_components/jquery-ui/themes/base/menu.css',
                    'bower_components/jquery-ui/themes/base/progressbar.css',
                    'bower_components/jquery-ui/themes/base/resizable.css',
                    'bower_components/jquery-ui/themes/base/selectable.css',
                    'bower_components/jquery-ui/themes/base/selectmenu.css',
                    'bower_components/jquery-ui/themes/base/sortable.css',
                    'bower_components/jquery-ui/themes/base/slider.css',
                    'bower_components/jquery-ui/themes/base/spinner.css',
                    'bower_components/jquery-ui/themes/base/tabs.css',
                    'bower_components/jquery-ui/themes/base/tooltip.css',
                    'bower_components/jquery-ui/themes/base/theme.css'
                ],
                dest: 'web/dist/<%= pkg.name %>_jq_ui.css'
            },
            css_preview: {
                options: {
                    stripBanners: {
                        block: true
                    },
                    banner: '<%= banner_format %>'
                },
                src: [
                    'web/dist/<%= pkg.name %>_jq_ui.css',
                    'bower_components/bootstrap/dist/css/bootstrap.css',
                    'web/css/pygments_table.css',
                    'web/css/pygments.css',
                    'web/css/blog.css'
                ],
                dest: 'web/dist/<%= pkg.name %>_preview.css'
            },
            css_main: {
                options: {
                    stripBanners: {
                        block: true
                    },
                    banner: '<%= banner_format %>'
                },
                src: [
                    'web/dist/<%= pkg.name %>_jq_ui.css',
                    'bower_components/bootstrap/dist/css/bootstrap.css',
                    'web/css/pygments_inline.css',
                    'web/css/pygments.css',
                    'web/css/symbola.css',
                    'web/css/main.css'
                ],
                dest: 'web/dist/<%= pkg.name %>_main.css'
            },
            js: {
                options: {
                    stripBanners: {
                        block: true
                    },
                    banner: '<%= banner_format %>'
                },
                src: [
                    'web/spa/assets/vendor.js',
                    'bower_components/bootstrap/dist/js/bootstrap.js',
                    'bower_components/moment/moment.js',
                    'bower_components/moment/locale/ru.js',
                    'bower_components/jquery-ui/ui/core.js',
                    'bower_components/jquery-ui/ui/widget.js',
                    'bower_components/jquery-ui/ui/position.js',
                    'bower_components/jquery-ui/ui/autocomplete.js',
                    'bower_components/jquery-ui/ui/menu.js',
                    'web/bundles/fosjsrouting/js/router.js',
                    'web/spa/assets/mtt-blog.js'
                ],
                dest: 'web/dist/<%= pkg.name %>.js'
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                format: 'keep-breaks',
                roundingPrecision: -1
            },
            target: {
                files: {
                    'web/dist/<%= pkg.name %>_login.min.css': ['<%= concat.css_login.dest %>'],
                    'web/dist/<%= pkg.name %>_tmp_preview.min.css': ['<%= concat.css_preview.dest %>'],
                    'web/dist/<%= pkg.name %>_tmp_main.min.css': ['<%= concat.css_main.dest %>']
                }
            }
        },
        uglify: {
            options: {
                output: {
                    ascii_only: true,
                    max_line_len: 160
                }
            },
            dist: {
                files: {
                    'web/dist/<%= pkg.name %>_tmp.min.js': ['<%= concat.js.dest %>']
                }
            }
        }
    });

    grunt.registerTask('build', ['concat', 'cssmin', 'uglify']);
    grunt.registerTask('style', ['concat', 'cssmin']);
    grunt.registerTask('default', ['build']);
};
