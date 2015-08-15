module.exports = function(grunt) {

    var source_path = 'src/Mtt/BlogBundle/Resources/public/';
    var files = {};

    files[source_path + 'app/templates.js'] = source_path + 'app/templates/**/*.hbs';

    grunt.initConfig({
        emberTemplates: {
            compile: {
                options: {
                    templateName: function(src) {
                        console.log(src);
                        return src.replace(/src\/Mtt\/BlogBundle\/Resources\/public\/app\/templates\//, '')
                            .replace(/\./g, '/');
                    },
                    templateCompilerPath: source_path + 'components/ember/ember-template-compiler.js',
                    handlebarsPath: source_path + 'components/handlebars/handlebars.js'
                },
                files: files
            }
        },
        watch: {
            scripts: {
                files: source_path + 'app/templates/**/*.hbs',
                tasks: 'emberTemplates'
            }
        }
    });

    grunt.loadNpmTasks('grunt-ember-templates');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['emberTemplates']);
};
