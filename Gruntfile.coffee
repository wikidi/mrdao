module.exports = (grunt) ->
  grunt.initConfig
    bump:
      files: ['package.json']
      push: false

  grunt.loadNpmTasks 'grunt-bump'

  grunt.registerTask 'default', []