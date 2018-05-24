var taskName = '';
module.exports = function (grunt) {
    var _ = require('lodash');
    // Load required Grunt tasks. These are installed based on the versions listed
    // * in 'package.json' when you do 'npm install' in this directory.
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-ng-annotate');
    grunt.loadNpmTasks('grunt-html2js');
    grunt.loadNpmTasks('grunt-angular-translate');
    grunt.loadNpmTasks('grunt-regex-replace');
    grunt.loadNpmTasks('grunt-zip');
    grunt.loadNpmTasks('grunt-ssh');
    grunt.loadNpmTasks('grunt-parallel');
    grunt.loadNpmTasks('grunt-exec');
    grunt.loadNpmTasks('grunt-ngdocs');
    /** ********************************************************************************* */
    /** **************************** File Config **************************************** */
    var fileConfig = {
        build_dir: 'public',
        compile_dir: 'public',
        /**
         * This is a collection of file patterns for our app code (the
         * stuff in 'src/'). These paths are used in the configuration of
         * build tasks. 'js' is all project javascript, except tests.
         * template HTML files, while 'appTemplates' contains the templates for
         * our app's code. 'html' is just our main HTML file. 'less' is our main
         * stylesheet, and 'unit' contains our app's unit tests.
         */
        app_files: {
            js: ['./client/src/assets/js/loader_build.js', '/client/src/assets/js/loader.js', 'vendor/jquery/dist/jquery.min.js', '/client/src/assets/js/ag-admin/loader_build.js', '/client/src/assets/js/ag-admin/loader.js'],
            copy_js: [
                './client/src/app/App.js',
                'client/src/app/Constant.js',
                'client/src/app/Common/*.module.js',
                'client/src/app/User/*.module.js',
                'client/src/app/Wallets/*.module.js',
                'client/src/app/Home/*.module.js',
                'client/src/app/Message/*.module.js',
                'client/src/app/Transactions/*.module.js',
                'client/src/app/Common/*.js',
                'client/src/app/User/*.js',
                'client/src/app/Wallets/*.js',
                'client/src/app/Home/*.js',
                'client/src/app/Message/*.js',
                'client/src/app/Transactions/*.js'
            ],
            appTemplates: [
                'client/src/app/Common/*.tpl.html',
                'client/src/app/User/*.tpl.html',
                'client/src/app/Wallets/*.tpl.html',
                'client/src/app/Home/*.tpl.html',
                'client/src/app/Message/*.tpl.html',
                'client/src/app/Transactions/*.tpl.html'
            ],
            html: ['client/src/index.html'],
            less: ['client/src/less/main.less']
        },
        /**
         *  This is a collection of files used during admin pages.
         */
        admin_files: {
            js: ['client/src/ag-admin/js/ng-admin.jwt-auth.js', 'client/src/ag-admin/js/ng-admin.app.js'],
            appTemplates: ['client/src/ag-admin/tpl/*.html'],
            less: [
                'client/src/less/admin/main.less'
            ]
        },
        /**
         * This is the same as 'app_files', except it contains patterns that
         * reference vendor code ('vendor/') that we need to place into the build
         * process somewhere. While the 'app_files' property ensures all
         * standardized files are collected for compilation, it is the user's job
         * to ensure non-standardized (i.e. vendor-related) files are handled
         * appropriately in 'vendor_files.js'.
         *
         * The 'vendor_files.js' property holds files to be automatically
         * concatenated and minified with our project source files.
         *
         * The 'vendor_files.css' property holds any CSS files to be automatically
         * included in our app.
         *
         * The 'vendor_files.assets' property holds any assets to be copied along
         * with our app's assets. This structure is flattened, so it is not
         * recommended that you use wildcards.
         */
        vendor_files: {
            js: [
                'vendor/moment/min/moment-with-locales.min.js',
                'vendor/jquery/dist/jquery.min.js',
                'vendor/angular/angular.js',
                'vendor/angular-moment/angular-moment.js',
                'vendor/angular-sanitize/angular-sanitize.js',
                'vendor/angular-resource/angular-resource.js',
                'vendor/angular-bootstrap/ui-bootstrap-tpls.min.js',
                'vendor/ng-file-upload-shim/angular-file-upload-shim.min.js',
                'vendor/ng-file-upload/ng-file-upload.min.js',
                'vendor/angular-ui-router/release/angular-ui-router.js',
                'vendor/angular-animate/angular-animate.js',
                'vendor/angular-translate/angular-translate.min.js',
                'vendor/satellizer/satellizer.js',
                'vendor/angular-growl-v2/build/angular-growl.js',
                'vendor/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js',
                'vendor/angular-dynamic-locale/tmhDynamicLocale.min.js',
                'vendor/angular-translate-storage-cookie/angular-translate-storage-cookie.js',
                'vendor/angular-translate-handler-log/angular-translate-handler-log.js',
                'vendor/angular-translate-storage-local/angular-translate-storage-local.min.js',
                'vendor/angular-cookies/angular-cookies.js',
                'vendor/angular-slugify/angular-slugify.js',
                'vendor/bootstrap/dist/js/bootstrap.min.js',
                'vendor/angulartics/dist/angulartics.min.js',
                'vendor/angulartics-google-analytics/dist/angulartics-google-analytics.min.js',
                'vendor/angulartics-facebook-pixel/dist/angulartics-facebook-pixel.min.js',
                'vendor/angular-recaptcha/release/angular-recaptcha.min.js',
                'vendor/angular-credit-cards/release/angular-credit-cards.js',
                'vendor/angular-loading-bar/build/loading-bar.min.js',
                'vendor/bootstrap-ui-datetime-picker/dist/datetime-picker.js',
                'vendor/accounting/accounting.js',
                'vendor/angularjs-slider/dist/rzslider.js',
                'vendor/angular-google-places-autocomplete/src/autocomplete.js',
                'vendor/angular-bootstrap-calendar/dist/js/angular-bootstrap-calendar-tpls.min.js',
                'vendor/angularjs-socialshare/dist/angular-socialshare.js'
            ],
            css: [
                'vendor/angular-growl-v2/build/angular-growl.min.css',
                'vendor/angular-loading-bar/build/loading-bar.min.css',
                'vendor/angularjs-slider/dist/rzslider.css',
                'vendor/angular-google-places-autocomplete/src/autocomplete.css',
                'vendor/angular-bootstrap-calendar/dist/css/angular-bootstrap-calendar.min.css'
            ],
            assets: ['vendor/angular-i18n/*.*'],
            moment_assets: ['vendor/moment/locale/*.*']
        },
        admin_vendor_files: {
            js: [
                'vendor/moment/min/moment-with-locales.min.js',
                'vendor/jquery/dist/jquery.min.js',
                'vendor/ng-admin/build/ng-admin.min.js',
                'vendor/angular-google-places-autocomplete/src/autocomplete.js',
                'vendor/bootstrap/dist/js/bootstrap.min.js',
                'vendor/ng-file-upload/ng-file-upload.min.js',
                'vendor/angular-bootstrap-calendar/dist/js/angular-bootstrap-calendar-tpls.min.js'
            ],
            css: [
                'client/src/less/admin/ng-admin.min.css',
                'client/src/less/admin/timeline.css',
                'vendor/angular-google-places-autocomplete/src/autocomplete.css',
                'vendor/angular-bootstrap-calendar/dist/css/angular-bootstrap-calendar.min.css'
            ],
            assets: []
        }
    };
    /** ********************************************************************************* */
    /** **************************** Task Config **************************************** */
    var taskConfig = {
        pkg: grunt.file.readJSON("package.json"),
        /**
         * The banner is the comment that is placed at the top of our compiled
         * source files. It is first processed as a Grunt template, where the 'less than percent equals'
         * pairs are evaluated based on this very configuration object.
         */
        meta: {
            banner: '/**\n' +
            ' * <%= pkg.name %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>\n' +
            ' *\n' +
            ' * Copyright (c) <%= grunt.template.today("yyyy") %> <%= pkg.author %>\n' +
            ' */\n'
        },
        /**
         * The directories to delete when 'grunt clean' is executed.
         */
        clean: {
            all: [
                '<%= build_dir %>/index.html',
                '<%= build_dir %>/assets/',
                '<%= build_dir %>/api/assets/js/',
                '<%= build_dir %>/Plugins/',
                '<%= build_dir %>/vendor/',
                '<%= build_dir %>/client/',
                '<%= build_dir %>/ag-admin/',
                '!<%= build_dir %>/api_explorer/**',
                '!<%= build_dir %>/.htaccess',
                '!<%= build_dir %>/index.php'
            ],
            compile: [
                '<%= build_dir %>/ag-admin/js/',
                '<%= build_dir %>/ag-admin/css/',
                '<%= build_dir %>/vendor/',
                '<%= build_dir %>/client/src/app/'
            ],
            vendor: [
                '<%= build_dir %>/vendor/'
            ],
            index: ['<%= build_dir %>/index.html']
        },
        'regex-replace': {
            'api-config': {
                src: ['public/api_explorer/index.html', 'public/api_explorer/api-docs/*.json', 'client/src/**/*.js'],
                actions: [{
                    name: 'Domain Replace',
                    search: '/bookorrent/public',
                    replace: '<%= config.api_url %>',
                    flags: 'g'
                }]
            }
        },
        zip: {
            'using-cwd': {
                cwd: './',
                src: [
                    'app/**',
                    'bootstrap/**',
                    'config/**',
                    'client/**',
                    'database/**',
                    'public/**',
                    'resources/**',
                    'storage/**',
                    'tests/**',
                    '.env.example',
                    'artisan',
                    'composer.json',
                    'phpunit.xml',
                    'readme.md'
                ],
                dest: 'bookorrent.zip'
            }
        },
        sftp: {
            dev1: {
                files: {
                    "./": 'bookorrent.zip'
                },
                options: {
                    path: '<%= config.upload_path %>',
                    host: '<%= config.host %>',
                    username: '<%= config.host_username %>',
                    password: '<%= config.host_password %>'
                }
            }
        },
        sshexec: {
            dev1: {
                command: ['unzip -o -d <%= config.upload_path %> <%= config.upload_path %>/bookorrent.zip', 'rm <%= config.upload_path %>/bookorrent.zip'],
                options: {
                    host: '<%= config.host %>',
                    username: '<%= config.host_username %>',
                    password: '<%= config.host_password %>'
                }
            }
        },
        /**
         * The 'copy' task just copies files from A to B. We use it here to copy
         * our project assets (images, fonts, etc.) and javascripts into
         * 'build_dir', and then to copy the assets to 'compile_dir'.
         */
        copy: {
            build_app_assets: {
                files: [
                    {
                        src: ['**'],
                        dest: '<%= build_dir %>/assets/',
                        cwd: 'client/src/assets',
                        expand: true
                    }
                ]
            },
            build_app_assets_js: {
                files: [
                    {
                        src: ['<%= app_files.js %>'],
                        dest: '<%= build_dir %>/assets/js/',
                        cwd: '.',
                        expand: true,
                        flatten: true
                    }
                ]
            },
            build_vendor_assets: {
                files: [
                    {
                        src: ['<%= vendor_files.assets %>'],
                        dest: '<%= build_dir %>/assets/js/angular-i18n/',
                        cwd: '.',
                        expand: true,
                        flatten: true
                    }
                ]
            },
            build_vendor_moment: {
                files: [
                    {
                        src: ['<%= vendor_files.moment_assets %>'],
                        dest: '<%= build_dir %>/assets/js/moment/locale',
                        cwd: '.',
                        expand: true,
                        flatten: true
                    }
                ]
            },
            build_ngadmin: {
                files: [
                    {
                        src: ['<%= admin_files.js %>'],
                        dest: '<%= build_dir %>/ag-admin/js/',
                        cwd: '.',
                        expand: true,
                        flatten: true
                    }
                ]
            },
            build_ngadmin_tpl: {
                files: [
                    {
                        src: ['<%= admin_files.appTemplates %>'],
                        dest: '<%= build_dir %>/ag-admin/tpl/',
                        cwd: '.',
                        expand: true,
                        flatten: true
                    }
                ]
            },
            build_appjs: {
                files: [
                    {
                        src: ['<%= app_files.copy_js %>'],
                        dest: '<%= build_dir %>/',
                        cwd: '.',
                        expand: true
                    }
                ]
            },
            build_vendorjs: {
                files: [
                    {
                        src: ['<%= vendor_files.js %>', '<%= admin_vendor_files.js %>'],
                        dest: '<%= build_dir %>/',
                        cwd: '.',
                        expand: true
                    }
                ]
            }
        },
        /**
         * 'grunt concat' concatenates multiple source files into a single file.
         */
        concat: {
            // The 'build_css' target concatenates compiled CSS and vendor CSS together.
            build_css: {
                src: [
                    '<%= build_dir %>/assets/css/<%= pkg.name %>-<%= pkg.version %>.css',
                    '<%= vendor_files.css %>'
                ],
                dest: '<%= build_dir %>/assets/css/<%= pkg.name %>-<%= pkg.version %>.css'
            },
            // The 'build_admin_css' target concatenates compiled CSS and vendor CSS together.
            build_admin_css: {
                src: [
                    '<%= build_dir %>/assets/css/<%= pkg.name %>-admin-<%= pkg.version %>.css',
                    '<%= admin_vendor_files.css %>'
                ],
                dest: '<%= build_dir %>/assets/css/<%= pkg.name %>-admin-<%= pkg.version %>.css'
            },
            // The 'compile_js' target concatenates app and vendor js code together.
            compile_js: {
                options: {
                    banner: '<%= meta.banner %>'
                },
                src: [
                    '<%= vendor_files.js %>',
                    'module.prefix',
                    '<%= build_dir %>/client/src/**/*.module.js',
                    '<%= build_dir %>/client/src/**/*.js',
                    '<%= html2js.app.dest %>',
                    'module.suffix'
                ],
                dest: '<%= compile_dir %>/assets/js/<%= pkg.name %>-<%= pkg.version %>.js'
            },
            compile_admin_js: {
                options: {
                    banner: '<%= meta.banner %>'
                },
                src: [
                    '<%= admin_vendor_files.js %>',
                    '<%= admin_files.js %>'
                ],
                dest: '<%= compile_dir %>/assets/js/<%= pkg.name %>-admin-<%= pkg.version %>.js'
            }
        },
        /**
         * 'ng-annotate' annotates the sources for safe minification. That is, it allows us
         * to code without the array syntax.
         */
        ngAnnotate: {
            options: {
                singleQuotes: true
            },
            build: {
                files: [
                    {
                        src: ['<%= app_files.copy_js %>'],
                        cwd: '<%= build_dir %>',
                        dest: '<%= build_dir %>',
                        expand: true
                    }
                ]
            }
        },
        /**
         * Minify the sources!
         */
        uglify: {
            compile: {
                options: {
                    banner: '<%= meta.banner %>',
                    mangle: false
                },
                files: {
                    '<%= concat.compile_js.dest %>': '<%= concat.compile_js.dest %>'
                }
            },
            admin_compile: {
                options: {
                    banner: '<%= meta.banner %>',
                    mangle: false //minified JS not working in admin side. So we added this option. (It works for front end compressed js)
                },
                files: {
                    '<%= concat.compile_admin_js.dest %>': '<%= concat.compile_admin_js.dest %>'
                }
            }
        },
        /**
         * `grunt-contrib-less` handles our LESS compilation and uglification automatically.
         * Only our 'main.less' file is included in compilation; all other files
         * must be imported from this file.
         */
        less: {
            build: {
                files: {
                    '<%= build_dir %>/assets/css/<%= pkg.name %>-<%= pkg.version %>.css': '<%= app_files.less %>'
                }
            },
            admin_build: {
                files: {
                    '<%= build_dir %>/assets/css/<%= pkg.name %>-admin-<%= pkg.version %>.css': '<%= admin_files.less %>'
                }
            },
            compile: {
                files: {
                    '<%= build_dir %>/assets/css/<%= pkg.name %>-<%= pkg.version %>.css': '<%= app_files.less %>'
                },
                options: {
                    cleancss: true,
                    compress: true
                }
            },
            admin_compile: {
                files: {
                    '<%= build_dir %>/assets/css/<%= pkg.name %>-admin-<%= pkg.version %>.css': '<%= admin_files.less %>'
                },
                options: {
                    cleancss: true,
                    compress: true
                }
            }
        },
        /**
         * 'jshint' defines the rules of our linter as well as which files we
         * should check. This file, all javascript sources, and all our unit tests
         * are linted based on the policies listed in 'options'. But we can also
         * specify exclusionary patterns by prefixing them with an exclamation
         * point (!); this is useful when code comes from a third party but is
         * nonetheless inside 'src/'.
         */
        jshint: {
            src: [
                '<%= app_files.copy_js %>'
            ],
            gruntfile: [
                'Gruntfile.js'
            ],
            options: {
                curly: true,
                immed: true,
                newcap: true,
                noarg: true,
                sub: true,
                boss: true,
                eqnull: true
            },
            globals: {}
        },
        /**
         * HTML2JS is a Grunt plugin that takes all of your template files and
         * places them into JavaScript files as strings that are added to
         * AngularJS's template cache. This means that the templates too become
         * part of the initial payload as one JavaScript file. Neat!
         */
        html2js: {
            // These are the templates from 'src/app'.
            app: {
                options: {
                    base: 'client/src/app'
                },
                src: ['<%= app_files.appTemplates %>'],
                dest: '<%= build_dir %>/assets/js/templates-app.js'
            }
        },
        /**
         * The 'index' task compiles the 'index.html' file as a Grunt template. CSS
         * and JS files co-exist here but they get split apart later.
         */
        index: {
            /**
             * During development, we don't want to have wait for compilation,
             * concatenation, minification, etc. So to avoid these steps, we simply
             * add all script files directly to the '<head>' of 'index.html'. The
             * 'src' property contains the list of included files.
             */
            build: {
                appName: 'BookorRent',
                dir: '<%= build_dir %>',
                src: [
                    '<%= build_dir %>/assets/js/jquery.min.js',
                    '<%= build_dir %>/assets/js/loader_build.js',
                    '<%= build_dir %>/assets/css/<%= pkg.name %>-<%= pkg.version %>.css'
                ]
            },
            /**
             * When it is time to have a completely compiled application, we can
             * alter the above to include only a single JavaScript and a single CSS
             * file. Now we're back!
             */
            compile: {
                appName: 'BookorRent',
                dir: '<%= compile_dir %>',
                src: [
                    '<%= build_dir %>/assets/js/jquery.min.js',
                    '<%= build_dir %>/assets/js/loader.js',
                    '<%= build_dir %>/assets/css/<%= pkg.name %>-<%= pkg.version %>.css'
                ]
            }
        },
        /**
         * The ng-admin 'index' task compiles the 'ng-admin/index.html' file as a Grunt template. CSS
         * and JS files co-exist here but they get split apart later.
         */
        admin_index: {
            /**
             * During development, we don't want to have wait for compilation,
             * concatenation, minification, etc. So to avoid these steps, we simply
             * add all script files directly to the '<head>' of 'index.html'. The
             * 'src' property contains the list of included files.
             */
            build: {
                appName: 'BookorRent',
                dir: '<%= build_dir %>',
                src: [
                    '<%= build_dir %>/assets/js/jquery.min.js',
                    '<%= build_dir %>/assets/js/ag-admin/loader_build.js',
                    '<%= build_dir %>/assets/css/<%= pkg.name %>-admin-<%= pkg.version %>.css'
                ]
            },
            compile_admin: {
                appName: 'BookorRent',
                dir: '<%= compile_dir %>',
                src: [
                    '<%= build_dir %>/assets/js/jquery.min.js',
                    '<%= build_dir %>/assets/js/ag-admin/loader.js',
                    '<%= build_dir %>/assets/css/<%= pkg.name %>-admin-<%= pkg.version %>.css'
                ]
            }
        },
        /**
         * i18nextract build json lang files
         */
        i18nextract: {
            // Getting all translate key to json files
            default_language: {
                suffix: '.json',
                src: ['client/src/index.html', 'client/src/app/**/*.js', 'client/src/app/**/*.html'],
                lang: ['en', 'es'],
                defaultLang: 'en',
                dest: 'client/src/assets/js/l10n'
            },
            // For filling dafault english value to fr_FR and ja-JP's value
            default_exists_i18n: {
                suffix: '.json',
                nullEmpty: true,
                src: ['client/src/index.html', 'client/src/app/**/*.js', 'client/src/app/**/*.html'],
                lang: ['es'],
                dest: 'client/src/assets/js/l10n/',
                source: 'client/src/assets/js/l10n/en.json' // Use to generate different output file
            }
        },
        ngdocs: {
            all: ['client/src/app/**/*.js'],
        }
    };
    /** ********************************************************************************* */
    /** **************************** Project Configuration ****************************** */
        // The following chooses some watch tasks based on whether we're running in mock mode or not.
        //  Our watch (delta above) needs to run a different index task and copyVendorJs task
        //  in several places if "grunt watchmock" is run.
    taskName = grunt.cli.tasks[0]; // the name of the task from the command line (e.g. "grunt watch" => "watch")
    // Take the big config objects we defined above, combine them, and feed them into grunt
    grunt.initConfig(_.assign(taskConfig, fileConfig));
    grunt.registerTask('build', [
        'clean:all',
        'html2js',
        'jshint',
        'less:build', 'less:admin_build', 'concat:build_css', 'concat:build_admin_css',
        'copy:build_app_assets', 'copy:build_app_assets_js', 'copy:build_ngadmin', 'copy:build_ngadmin_tpl', 'copy:build_vendor_assets', 'copy:build_vendor_moment', 'copy:build_appjs', 'copy:build_vendorjs',
        'ngAnnotate:build',
        'index:build',
        'admin_index:build'
    ]);
    // 'coffeelint', 'coffee', 'copy:build_vendorcss', 'copy:build_admin_vendorcss',
    // The 'compile' task gets your app ready for deployment by concatenating and minifying your code.
    // Note - compile builds off of the build dir (look at concat:compile_js), so run grunt build before grunt compile
    grunt.registerTask('compile', 'compile task', function (env) {
        if (env === undefined) {
            grunt.task.run(['clean:all', 'html2js', 'jshint', 'less:compile', 'less:admin_compile', 'concat:build_css', 'concat:build_admin_css',
                'copy:build_app_assets', 'copy:build_app_assets_js', 'copy:build_ngadmin', 'copy:build_ngadmin_tpl', 'copy:build_vendor_assets', 'copy:build_vendor_moment', 'copy:build_appjs', 'copy:build_vendorjs',
                'ngAnnotate:build', 'concat:compile_js', 'concat:compile_admin_js', 'uglify', 'index:compile',
                'admin_index:compile_admin', 'clean:compile']);
        } else {
            grunt.config.set('config', grunt.file.readJSON('grunt-config/' + env + '.json'));
            grunt.task.run(['clean:all', 'html2js', 'jshint', 'less:compile', 'less:admin_compile', 'concat:build_css', 'concat:build_admin_css',
                'copy:build_app_assets', 'copy:build_app_assets_js', 'copy:build_ngadmin', 'copy:build_ngadmin_tpl', 'copy:build_vendor_assets', 'copy:build_vendor_moment', 'copy:build_appjs', 'copy:build_vendorjs',
                'ngAnnotate:build', 'concat:compile_js', 'concat:compile_admin_js', 'uglify',
                'index:compile', 'admin_index:compile_admin', 'clean:compile']);
        }
    });
    grunt.registerTask('upload', 'Upload task', function (env) {
        if (env === undefined) {
            grunt.warn('Theme must be specified, like default:udemy.');
        }
        grunt.config.set('config', grunt.file.readJSON('grunt-config/' + env + '.json'));
        grunt.task.run(['regex-replace', 'compile:' + env, 'zip', 'sftp', 'sshexec']);
    });
    // A utility function to get all app JavaScript sources.
    function filterForJS(files) {
        return files.filter(function (file) {
            return file.match(/\.js$/);
        });
    }

    // A utility function to get all app CSS sources.
    function filterForCSS(files) {
        return files.filter(function (file) {
            return file.match(/\.css$/);
        });
    }

    // The index.html template includes the stylesheet and javascript sources
    // based on dynamic names calculated in this Gruntfile. This task assembles
    // the list into variables for the template to use and then runs the
    // compilation.
    grunt.registerMultiTask('index', 'Process index.html template', function () {
        var dirRE = new RegExp('^(' + grunt.config('build_dir') + '|' + grunt.config('compile_dir') + ')\/', 'g');
        // this.fileSrc comes from either build:src, compile:src, or karmaconfig:src in the index config defined above
        // see - http://gruntjs.com/api/inside-tasks#this.filessrc for documentation
        var jsFiles = filterForJS(this.filesSrc).map(function (file) {
            return file.replace(dirRE, '');
        });
        var cssFiles = filterForCSS(this.filesSrc).map(function (file) {
            return file.replace(dirRE, '');
        });
        var app = this.data.appName;
        // this.data.dir comes from either build:dir, compile:dir, or karmaconfig:dir in the index config defined above
        // see - http://gruntjs.com/api/inside-tasks#this.data for documentation
        grunt.file.copy('client/src/index.html', this.data.dir + '/index.html', {
            process: function (contents, path) {
                // These are the variables looped over in our index.html exposed as "scripts", "styles", and "version"
                return grunt.template.process(contents, {
                    data: {
                        appName: app,
                        scripts: jsFiles,
                        styles: cssFiles,
                        version: grunt.config('pkg.version'),
                        author: grunt.config('pkg.author'),
                        date: grunt.template.today("yyyy")
                    }
                });
            }
        });
    });
    // The index.html template includes the stylesheet and javascript sources
    // based on dynamic names calculated in this Gruntfile. This task assembles
    // the list into variables for the template to use and then runs the
    // compilation.
    grunt.registerMultiTask('admin_index', 'Process ag-admin/index.html template', function () {
        var dirRE = new RegExp('^(' + grunt.config('build_dir') + '|' + grunt.config('compile_dir') + ')\/', 'g');
        // this.fileSrc comes from either build:src, compile:src, or karmaconfig:src in the index config defined above
        // see - http://gruntjs.com/api/inside-tasks#this.filessrc for documentation
        var jsNgAdminFiles = filterForJS(this.filesSrc).map(function (file) {
            return file.replace(dirRE, '');
        });
        var cssAdminFiles = filterForCSS(this.filesSrc).map(function (file) {
            return file.replace(dirRE, '');
        });
        var app = this.data.appName;
        // this.data.dir comes from either build:dir, compile:dir, or karmaconfig:dir in the index config defined above
        // see - http://gruntjs.com/api/inside-tasks#this.data for documentation
        grunt.file.copy('client/src/ag-admin/index.html', this.data.dir + '/ag-admin/index.html', {
            process: function (contents, path) {
                // These are the variables looped over in our index.html exposed as "scripts", "styles", and "version"
                return grunt.template.process(contents, {
                    data: {
                        appName: app,
                        ngAdminScripts: jsNgAdminFiles,
                        styles: cssAdminFiles,
                        version: grunt.config('pkg.version'),
                        author: grunt.config('pkg.author'),
                        date: grunt.template.today("yyyy")
                    }
                });
            }
        });
    });
};
