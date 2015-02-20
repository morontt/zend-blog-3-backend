module.exports = function(grunt) {

    grunt.initConfig({
        emberTemplates: {
            compile: {
                options: {
                    templateName: function(src) {
                        return src.replace(/src\/Mtt\/BlogBundle\/Resources\/public\/app\/templates\//, '').replace('.', '/');
                    },
                    templateCompilerPath: 'src/Mtt/BlogBundle/Resources/public/components/ember/ember-template-compiler.js',
                    handlebarsPath: 'src/Mtt/BlogBundle/Resources/public/components/handlebars/handlebars.js'
                },
                files: {
                    "src/Mtt/BlogBundle/Resources/public/app/templates.js": "src/Mtt/BlogBundle/Resources/public/app/templates/*.hbs"
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-ember-templates');
    grunt.registerTask('default', ['emberTemplates']);
};
