module.exports = (grunt) ->
  grunt.initConfig
    bump:
      files: ['package.json']
      push: false
    exec:
      test:
        cmd: "vendor/bin/tester tests -c tests/php-unix.ini -j 10"

  grunt.loadNpmTasks 'grunt-bump'
  grunt.loadNpmTasks 'grunt-exec'

  grunt.registerTask 'test', ['exec:test']
  grunt.registerTask 'default', []