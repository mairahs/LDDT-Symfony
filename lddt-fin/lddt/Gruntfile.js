module.exports = function(grunt) {
    /**********************************
     *  Configuration des modules
     **********************************/
    grunt.initConfig({
        symlink: {
            app: {
                dest: 'web/bundles/app',
                relativeSrc: '../../app/Resources/public/',
                options: {type: 'dir'} // 'file' by default
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    'web/built/app/css/lddt.min.css':
                        ['app/Resources/public/css/override.css',
                         'app/Resources/public/css/custom.css']
                }
            }
        },
        watch: {
            options: {
                livereload: true
            },
            css: {
                files: 'web/bundles/*/css/*.css',
                tasks: ['cssmin']
            }
        }

    });

    /**********************************
     *  Chargement des modules Grunt
     **********************************/
    grunt.loadNpmTasks("grunt-symlink");
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');

    /**********************************
     *  Déclaration des tasks pour
     *  la ligne de commande
     **********************************/
    grunt.registerTask("default",['symlink','cssmin']);


}

