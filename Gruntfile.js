module.exports = function (grunt) {
    'use strict';

    grunt.initConfig({
        phpunit: {
            options: {
                bin: 'vendor/bin/phpunit',
                configuration: 'phpunit.xml.dist',
                colors: true,
                followOutput: true
            },
            test: {
                dir: 'tests'
            }
        },

        watch: {
            php: {
                tasks: ['phpunit'],
                files: [
                    'tests/**/*.php',
                    'src/**/*.php'
                ]
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-phpunit');

    grunt.registerTask('default', ['watch']);
};
