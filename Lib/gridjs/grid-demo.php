
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Vue.js dashboard list</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
    svg.icon {
        display: inline-block;
        width: 1em;
        height: 1em;
        stroke-width: 0;
        stroke: currentColor;
        fill: currentColor;
        vertical-align: -0.15em;
    }
    .arrow {
        display: inline-block;
        vertical-align: middle;
        width: 0;
        height: 0;
        margin-left: 5px;
        opacity: 0.66;
    }

    .arrow.asc {
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-bottom: 4px solid #000;
    }

    .arrow.dsc {
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 4px solid #000;
    }
    div:empty{
        width: 5em;
        height: 1em;
    }
    .table .form-control{
        cursor: pointer;
        border: 1px solid transparent;
    }
    .table .form-control:focus{
        border-color: blue;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
    table td{vertical-align: middle!important;}
    td:nth-child(n+4):nth-child(-n+9){
        text-align: center;
        width: 3rem;
    }
    th:nth-child(n+4):nth-child(-n+9) .d-flex{
        justify-content: center !important;
    }
    [v-cloak] { display: none; }
    </style>
  </head>
  <body>


    <!-- MAIN TEMPLATE CONTAINER -->

    <div class="container">
        <div id="app">
            <div class="alert" :class="{'alert-warning':true}" v-if="status.message" v-cloak>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="alert-heading" if="status.title">{{ status.title }}</h4>
                {{ status.message }}
            </div>
            <div class="d-flex justify-content-between">
                <h2><?php echo _('Dashboards') ?> <svg class="icon text-info"><use xlink:href="#icon-dashboard"></use></svg> <small v-if="gridData.length === 0">
                    <a href="<?php echo $path ?>" class="btn btn-success" :title="_('Reload') + '&hellip;'">
                    <svg class="icon"><use xlink:href="#icon-spinner11"></use></svg>
                    </a>
                </small></h2>
                <form id="search" class="form-inline position-relative">
                    <div class="form-group">
                        <input id="search-box" name="query" v-model="searchQuery" type="search" class="form-control mb-0" aria-describedby="searchHelp" placeholder="<?php echo _('Search') ?>" title="<?php echo _('Search the data by any column') ?>">
                        <button id="searchclear" @click.prevent="searchQuery = ''"style="right:0" class="btn btn-link position-absolute" :class="{'d-none':searchQuery.length===0}"><svg class="icon"><use xlink:href="#icon-close"></use></svg></button>
                        <small id="searchHelp" class="form-text text-muted sr-only"><?php echo _('Search the data by any column') ?>.</small>
                    </div>
                </form>
            </div>

            <!-- custom component to display grid data-->
            <grid-data :grid-data="gridData"
              :columns="gridColumns"
              :filter-key="searchQuery"
              :caption="status.title"
              @update:total="status=arguments[0]"
            >
            </grid-data>
        </div>

    </div><!-- eof .container -->

    <!-- END MAIN TEMPLATE CONTAINER ----------------------------------------------------------------  -->


    
    <script src="/emoncms/Lib/vue.min.js"></script>
    <script src="/emoncms/Lib/jquery-1.11.3.min.js"></script>
    <script src="/emoncms/Lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="/emoncms/Modules/dashboard/dashboard.js"></script>
    <script src="/emoncms/Lib/misc/gettext.js"></script>
    <script>
        /**
         * return plain js object with gettext translated strings
         * @return object
         */
        function getTranslations(){
            return {
                'Error': "<?php echo _('Error') ?>",
                'Error loading': "<?php echo _('Error loading') ?>",
                'Found %s entries': "<?php echo _('Found %s entries') ?>",
                'JS Error': "<?php echo _('JS Error') ?>",
                'Reload': "<?php echo _('Reload') ?>",
                'Loading': "<?php echo _('Loading') ?>…",
                'Saving': "<?php echo _('Saving') ?>…",
                'Label this dashboard with a name': "<?php echo _('Label this dashboard with a name') ?>",
                'Short title to use in URL.\neg \"roof-solar\"': "<?php echo _('Short title to use in URL.\neg \"roof-solar\"') ?>",
                'Adds a \"Default Dashboard\" bookmark in the sidebar.\nAlso visible at \"dashboard/view\"': "<?php echo _('Adds a \"Default Dashboard\" bookmark in the sidebar.\nAlso visible at \"dashboard/view\"') ?>",
                'Allow this Dashboard to be viewed by anyone': "<?php echo _('Allow this Dashboard to be viewed by anyone') ?>",
                'Clone the layout of this dashboard to a new Dashboard': "<?php echo _('Clone the layout of this dashboard to a new Dashboard') ?>",
                'Edit this dashboard layout': "<?php echo _('Edit this dashboard layout') ?>",
                'Delete this dashboard': "<?php echo _('Delete this dashboard') ?>…",
                'View this dashboard': "<?php echo _('View this dashboard') ?>…",
                'Edit Layout': "<?php echo _('Edit Layout') ?>"
            }
        }
    </script>

    <!-- START GRIDJS INCLUDE ---------------------------------------------------------------- -->
    <?php
        // @todo: include these with a webpack build script
        $path = '/var/www/html/emoncms/';
        include_once($path.'Lib/gridjs/grid.html');
    ?>
    <!-- END GRIDJS INCLUDE ---------------------------------------------------------------- -->

    

<!-- PAGE SPECIFIC SCRIPTS -->
<script>
    // remove this when integrated into emoncms path already a global variable
    var path = "http://localhost/emoncms/";
    var _DEBUG_ = false;
    Vue.config.productionTip = false;
    // filter available to all compenonets
    Vue.filter('capitalize', function(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    });
    var _debug = {
        log: function(){
            if(typeof _DEBUG_ !== 'undefined' && _DEBUG_) {
                console.trace.apply(this,arguments);
            }
        },
        error: function(){
            if(typeof _DEBUG_ !== 'undefined' && _DEBUG_) {
                console.error('Error')
                console.trace.apply(this, arguments);
            }
        }
    }
    var app = new Vue({
        el: "#app",
        data: {
            wait: 800, // time to wait before sending data
            statusData: {}, // store app status information
            searchQuery: "", // search string
            gridData: [], // array of grid items
            gridColumns: { // each gridData[] item property has a matching gridColumns property name
                id: {
                    sort: true
                },
                name: {
                    sort: true,
                    input: true,
                    title: _('Label this dashboard with a name')
                },
                alias: {
                    sort: true,
                    input: true,
                    title: _('Short title to use in URL.\neg \"roof-solar\"')
                },
                main: {
                    sort: true,
                    icon: '#icon-star_border',
                    label: _('default'),
                    title: _('Adds a \"Default Dashboard\" bookmark in the sidebar.\nAlso visible at \"dashboard/view\"')
                },
                public: {
                    sort: true,
                    icon: '#icon-earth',
                    title: _('Allow this Dashboard to be viewed by anyone')
                },
                clone: {
                    icon: '#icon-content_copy',
                    noHeader: true,
                    title: _('Clone the layout of this dashboard to a new Dashboard')
                },
                edit: {
                    icon: '#icon-cog',
                    noHeader: true,
                    link: true,
                    label: _('Edit Layout'),
                    title: _('Edit this dashboard layout')
                },
                delete: {
                    icon: '#icon-bin',
                    noHeader: true,
                    title: _('Delete this dashboard')
                },
                view: {
                    icon: '#icon-arrow_forward',
                    noHeader: true,
                    link: true,
                    title: _('View this dashboard')
                }
            }

        },
        watch: {
            gridData: {
                handler: function(val){
                    _debug.log('#app:gridData::changed')
                    this.Notify(val.length);
                },
                deep: true
            }
        },
        computed: {
            status: {
                get: function() {
                    return this.statusData
                },
                set: function(value){
                    let status = JSON.parse(JSON.stringify(this.statusData))
                    status.title = ''
                    status.message = ''
                    this.statusData = status;
                    switch (typeof value) {
                        case 'object':
                            this.statusData = value
                            break;
                        case 'number':
                            this.statusData.total = value
                            this.statusData.title =  _('Found %s entries').replace('%s', value)
                            break;
                        case 'string':
                            this.statusData.title = value
                            break;
                    }
                }
            }
        },
        mounted: function () {
            // on load request server data
            let vm = this;
            vm.Notify(_('Loading'), true)
            dashboard_v2.list().then(function(data){
                // handle success - populate gridData[] array
                // add urls for edit and view
                data.forEach(function(v,i){
                    let id = data[i].id;
                    data[i].view = path + 'dashboard/view?id=' + id;
                    data[i].edit = path + 'dashboard/edit?id=' + id;
                });
                vm.gridData = data;

            }, function(xhr,message){
                vm.Notify = ({
                    success: false,
                    title: _('Error loading.'),
                    message: message,
                    total: 0,
                    url: this.url
                }, true)
            })
            this._timeouts = {}
        },
        created: function () {
            // pass on root event handler to relevant function
            this.$root.$on('event:handler', function(event, item, property, value, success, error) {
                // set the global dataStore
                if (typeof success === 'function') {
                    success = function(){
                        item[property] = value;
                        success();
                    }
                } else {
                    success = function() {
                        item[property] = value;
                    }
                }
                // pass on the
                this.Handler(event, item, property, value, success, error);
            })
        },
        methods : {
            // ---------------------------------------
            // METHOD NAME MATCHES gridColumns{} KEYS (aka field names):
            // ---------------------------------------

            /**
             * update the name property of an individual entry
             * pass success() or error() to ajax promise to execute on finish
             *
             * @event event:handler
             *
             * @param Event event
             * @param Object item
             * @param String property
             * @param mixed value
             * @param Function success
             * @param Function error
             * @return void
             *
             */
            name: function(event, item, property, value, success, error){
                try {
                    this.Set_field_delayed(event, item, property, value, success, error)
                } catch (error) {
                    _debug.error (_('JS Error'), field, error, arguments);
                }
            },
            alias: function(event, item, property, value, success, error){
                try {
                    this.Set_field_delayed(event, item, property, value, success, error)
                } catch (error) {
                    _debug.error (_('JS Error'), field, error, arguments);
                }
            },
            main: function(event, item){
                // "..there can be only one..!" -Highlander '86
                try {
                    var vm = this;
                    var id = item.id
                    var field = 'main';
                    var value = !item[field];

                    // only modify view on success
                    this.Set_field(event, item, id, field, value, function() {
                        // remove all default entries
                        vm.gridData.forEach(function(row, i){
                            row[field] = false;
                        })
                        // set this one to default
                        item[field] = value;
                    });

                } catch (error) {
                    _debug.error (_('JS Error'), field, error, arguments);
                }
            },
            public: function(event, item){
                // toggle public status
                try {
                    var id = item.id
                    var field = 'public';
                    var value = !item[field];
                    this.Set_field(event, item, id, field, value, function() {
                        item[field] = value;
                    });
                } catch (error) {
                    _debug.error (_('JS Error'), field, error, arguments);
                }
            },
            clone: function(event, item){
                // clone item
                try {
                    let clone = Vue.util.extend({}, item);
                    let self = this;
                    dashboard_v2.clone(item.id).then(
                    function(insert_id){
                        clone.id = insert_id;
                        clone.name += ' clone';
                        clone.main = false;
                        clone.public = false;
                        clone.alias = '';
                        self.gridData.push(clone);
                    }, function(){
                        // @todo: handle error
                    })
                } catch (error) {
                    _debug.error (_('JS Error'), field, error, arguments);
                }
            },
            delete: function(event, item){
                // delete item
                try {
                    let title = _('Delete "%s"').replace('%s',item.name);
                    let question = [
                        title,
                        _("Deleting a dashboard is permanent"),
                        "\n",
                        _("Are you sure you want to delete ?")
                    ]
                    let max = 0;
                    question.forEach(function(item){
                        if (item.length > max) max = item.length;
                    })
                    question.splice(1,0,'―'.repeat(max/1.9));
                    let confirmation = question.join("\n");

                    if(confirm(confirmation)){
                        var self = this;
                        dashboard_v2.remove(item.id)
                        .then(function(){
                            var index = self.gridData.indexOf(item)
                            self.gridData.splice(index, 1);
                        })
                    }
                } catch (error) {
                    _debug.error (_('JS Error'), error, arguments);
                }
            },
            // ----------
            // UTILITIES
            // ----------

            /**
             * CALL CUSTOM FUNCTION FOR EACH FIELD
             * called by inputs and clicks
             *
             * @param Event event
             * @param Object item
             * @param String property
             * @param mixed value
             * @param Function success
             * @param Function error
             * @return void
             */
            Handler: function(event, item, property, value, success, error) {
                if(typeof this[property] === 'function') {
                    // call the fields' function, passing the dataGrid[] item that matches the index
                    this[property](event, item, property, value, success, error);
                }
            },
            /**
             * send data to server. runs success() and error() as required
             *
             * @param Event event
             * @param Object item
             * @param String property
             * @param mixed value
             * @param Function success
             * @param Function error
             * @return void
             */
            Set_field: function(event, item, id, field, value, success, error) {
                // sanitize input
                // @todo: more work could be done to check all the possible inputs
                switch(typeof value) {
                    case 'string':
                        _value = value.trim();
                        break;
                    default:
                        _value = value;
                }
                _debug.log('#app:Set_field()', {item, id, field, _value})

                // set the dashboard value calling success() or error() on completion
                dashboard_v2.set(field, id, _value).then(success, error);
            },
            /**
             * wait for pause in user input before sending data to server
             */
            Set_field_delayed: function(event, item, property, value, success, error){
                var vm = this;
                var timeout_key = item.id+'_'+property;
                window.clearTimeout(this._timeouts[timeout_key]);
                this._timeouts[timeout_key] = window.setTimeout( function() {
                    // call set field with parameters and callback functions
                    // for succesfull ajax transaction or error
                    // for fast servers you dont need this, as the save happens before you see it
                    saving = window.setTimeout(function(){
                        vm.Notify({
                            'title': _('Saving')
                        }, true)
                    }, vm.wait)

                    vm.Set_field(event, item, item.id, property, value,
                        // on success
                        function(data, message, xhr){
                            // on succesful save ...
                            window.clearTimeout(saving)
                            // display success message to user
                            _debug.log (_('SUCCESS'), message, arguments);
                            vm.Notify(_('Saved'))
                            if (typeof success == 'function') {
                                success(event)
                            }
                        },
                        // on error ...
                        function(xhr, message) {
                            // display error message to user
                            vm.Notify(message)
                            if (typeof error == 'function') {
                                error(event)
                            }
                            // pass error to catch statement
                            throw ['500_'+property, message].join(' ');
                        }
                    )
                }, this.wait);
            },
            /**
             * display feedback to user
             */
            Notify: function(status, persist) {
                // display message to user
                this.status = status
                vm = this
                // stop previous delay
                window.clearTimeout(this.statusTimeout);
                if(!persist) {
                    // wait until status is reset
                    this.statusTimeout = window.setTimeout(function(){
                        // reset to show the total
                        vm.status = vm.status.total;
                    }, this.wait * 3);
                }
            }
        }
    });

</script>

<!-- ICONS --------------------------------------------- -->
<svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <defs>
        <symbol id="icon-dashboard" viewBox="0 0 32 32">
            <!-- <title>dashboard</title> -->
            <path d="M17.313 4h10.688v8h-10.688v-8zM17.313 28v-13.313h10.688v13.313h-10.688zM4 28v-8h10.688v8h-10.688zM4 17.313v-13.313h10.688v13.313h-10.688z"></path>
        </symbol>
        <symbol id="icon-format_list_bulleted" viewBox="0 0 32 32">
            <!-- <title>format_list_bulleted</title> -->
            <path d="M9.313 6.688h18.688v2.625h-18.688v-2.625zM9.313 17.313v-2.625h18.688v2.625h-18.688zM9.313 25.313v-2.625h18.688v2.625h-18.688zM5.313 22c1.125 0 2 0.938 2 2s-0.938 2-2 2-2-0.938-2-2 0.875-2 2-2zM5.313 6c1.125 0 2 0.875 2 2s-0.875 2-2 2-2-0.875-2-2 0.875-2 2-2zM5.313 14c1.125 0 2 0.875 2 2s-0.875 2-2 2-2-0.875-2-2 0.875-2 2-2z"></path>
        </symbol>
        <symbol id="icon-home" viewBox="0 0 32 32">
            <!-- <title>home</title> -->
            <path d="M13.313 26.688h-6.625v-10.688h-4l13.313-12 13.313 12h-4v10.688h-6.625v-8h-5.375v8z"></path>
        </symbol>
        <symbol id="icon-input" viewBox="0 0 32 32">
            <!-- <title>input</title> -->
            <path d="M14.688 21.313v-4h-13.375v-2.625h13.375v-4l5.313 5.313zM28 4c1.438 0 2.688 1.188 2.688 2.688v18.688c0 1.438-1.25 2.625-2.688 2.625h-24c-1.438 0-2.688-1.188-2.688-2.625v-5.375h2.688v5.375h24v-18.75h-24v5.375h-2.688v-5.313c0-1.438 1.25-2.688 2.688-2.688h24z"></path>
        </symbol>
        <symbol id="icon-show_chart" viewBox="0 0 32 32">
            <!-- <title>show_chart</title> -->
            <path d="M4.688 24.625l-2-2 10-10 5.313 5.375 9.438-10.625 1.875 1.875-11.313 12.75-5.313-5.375z"></path>
        </symbol>
        <symbol id="icon-bullhorn" viewBox="0 0 32 32">
            <!-- <title>bullhorn</title> -->
            <path d="M32 13.414c0-6.279-1.837-11.373-4.109-11.413 0.009-0 0.018-0.001 0.027-0.001h-2.592c0 0-6.088 4.573-14.851 6.367-0.268 1.415-0.438 3.102-0.438 5.047s0.171 3.631 0.438 5.047c8.763 1.794 14.851 6.367 14.851 6.367h2.592c-0.009 0-0.018-0.001-0.027-0.001 2.272-0.040 4.109-5.134 4.109-11.413zM27.026 23.102c-0.293 0-0.61-0.304-0.773-0.486-0.395-0.439-0.775-1.124-1.1-1.979-0.727-1.913-1.127-4.478-1.127-7.223s0.4-5.309 1.127-7.223c0.325-0.855 0.705-1.54 1.1-1.979 0.163-0.182 0.48-0.486 0.773-0.486s0.61 0.304 0.773 0.486c0.395 0.439 0.775 1.124 1.1 1.979 0.727 1.913 1.127 4.479 1.127 7.223s-0.4 5.309-1.127 7.223c-0.325 0.855-0.705 1.54-1.1 1.979-0.163 0.181-0.48 0.486-0.773 0.486zM7.869 13.414c0-1.623 0.119-3.201 0.345-4.659-1.48 0.205-2.779 0.323-4.386 0.323-2.096 0-2.096 0-2.096 0l-1.733 2.959v2.755l1.733 2.959c0 0 0 0 2.096 0 1.606 0 2.905 0.118 4.386 0.323-0.226-1.458-0.345-3.036-0.345-4.659zM11.505 20.068l-4-0.766 2.558 10.048c0.132 0.52 0.648 0.782 1.146 0.583l3.705-1.483c0.498-0.199 0.698-0.749 0.444-1.221l-3.853-7.161zM27.026 17.148c-0.113 0-0.235-0.117-0.298-0.187-0.152-0.169-0.299-0.433-0.424-0.763-0.28-0.738-0.434-1.726-0.434-2.784s0.154-2.046 0.434-2.784c0.125-0.33 0.272-0.593 0.424-0.763 0.063-0.070 0.185-0.187 0.298-0.187s0.235 0.117 0.298 0.187c0.152 0.169 0.299 0.433 0.424 0.763 0.28 0.737 0.434 1.726 0.434 2.784s-0.154 2.046-0.434 2.784c-0.125 0.33-0.272 0.593-0.424 0.763-0.063 0.070-0.185 0.187-0.298 0.187z"></path>
        </symbol>
        <symbol id="icon-user-check" viewBox="0 0 32 32">
            <!-- <title>user-check</title> -->
            <path d="M30 19l-9 9-3-3-2 2 5 5 11-11z"></path>
            <path d="M14 24h10v-3.598c-2.101-1.225-4.885-2.066-8-2.321v-1.649c2.203-1.242 4-4.337 4-7.432 0-4.971 0-9-6-9s-6 4.029-6 9c0 3.096 1.797 6.191 4 7.432v1.649c-6.784 0.555-12 3.888-12 7.918h14v-2z"></path>
        </symbol>
        <symbol id="icon-wrench" viewBox="0 0 32 32">
            <!-- <title>wrench</title> -->
            <path d="M31.342 25.559l-14.392-12.336c0.67-1.259 1.051-2.696 1.051-4.222 0-4.971-4.029-9-9-9-0.909 0-1.787 0.135-2.614 0.386l5.2 5.2c0.778 0.778 0.778 2.051 0 2.828l-3.172 3.172c-0.778 0.778-2.051 0.778-2.828 0l-5.2-5.2c-0.251 0.827-0.386 1.705-0.386 2.614 0 4.971 4.029 9 9 9 1.526 0 2.963-0.38 4.222-1.051l12.336 14.392c0.716 0.835 1.938 0.882 2.716 0.104l3.172-3.172c0.778-0.778 0.731-2-0.104-2.716z"></path>
        </symbol>
        <symbol id="icon-leaf" viewBox="0 0 32 32">
            <!-- <title>leaf</title> -->
            <path d="M31.604 4.203c-3.461-2.623-8.787-4.189-14.247-4.189-6.754 0-12.257 2.358-15.099 6.469-1.335 1.931-2.073 4.217-2.194 6.796-0.108 2.296 0.278 4.835 1.146 7.567 2.965-8.887 11.244-15.847 20.79-15.847 0 0-8.932 2.351-14.548 9.631-0.003 0.004-0.078 0.097-0.207 0.272-1.128 1.509-2.111 3.224-2.846 5.166-1.246 2.963-2.4 7.030-2.4 11.931h4c0 0-0.607-3.819 0.449-8.212 1.747 0.236 3.308 0.353 4.714 0.353 3.677 0 6.293-0.796 8.231-2.504 1.736-1.531 2.694-3.587 3.707-5.764 1.548-3.325 3.302-7.094 8.395-10.005 0.292-0.167 0.48-0.468 0.502-0.804s-0.126-0.659-0.394-0.862z"></path>
        </symbol>
        <symbol id="icon-phonelink_setup" viewBox="0 0 32 32">
            <!-- <title>phonelink_setup</title> -->
            <path d="M25.313 1.313c1.438 0 2.688 1.25 2.688 2.688v24c0 1.438-1.25 2.688-2.688 2.688h-13.313c-1.438 0-2.688-1.25-2.688-2.688v-4h2.688v2.688h13.313v-21.375h-13.313v2.688h-2.688v-4c0-1.438 1.25-2.688 2.688-2.688h13.313zM10.688 18.688c1.438 0 2.625-1.25 2.625-2.688s-1.188-2.688-2.625-2.688-2.688 1.25-2.688 2.688 1.25 2.688 2.688 2.688zM15.75 16.688l1.438 1.188c0.125 0.125 0.25 0.25 0.125 0.375l-1.313 2.313c-0.125 0.125-0.25 0.125-0.375 0.125l-1.75-0.688c-0.375 0.25-0.813 0.563-1.188 0.688l-0.313 1.688c-0.125 0.125-0.25 0.313-0.375 0.313h-2.688c-0.125 0-0.375-0.188-0.25-0.313l-0.25-1.688c-0.375-0.125-0.813-0.438-1.188-0.688l-1.875 0.563c-0.125 0.125-0.313-0.063-0.438-0.188l-1.313-2.25c0-0.125 0-0.25 0.125-0.5l1.5-1.063v-1.375l-1.5-1.063c-0.125-0.125-0.25-0.25-0.125-0.375l1.313-2.313c0.125-0.125 0.313-0.125 0.438-0.125l1.688 0.688c0.375-0.25 0.875-0.563 1.25-0.688l0.25-1.688c0.125-0.125 0.25-0.313 0.375-0.313h2.688c0.25 0 0.375 0.188 0.375 0.313l0.313 1.688c0.375 0.125 0.813 0.438 1.188 0.688l1.75-0.563c0.125-0.125 0.25 0.063 0.375 0.188l1.313 2.25c0 0.125 0 0.25-0.125 0.375l-1.438 1.063v1.375z"></path>
        </symbol>
        <symbol id="icon-plus" viewBox="0 0 32 32">
            <!-- <title>plus</title> -->
            <path d="M31 12h-11v-11c0-0.552-0.448-1-1-1h-6c-0.552 0-1 0.448-1 1v11h-11c-0.552 0-1 0.448-1 1v6c0 0.552 0.448 1 1 1h11v11c0 0.552 0.448 1 1 1h6c0.552 0 1-0.448 1-1v-11h11c0.552 0 1-0.448 1-1v-6c0-0.552-0.448-1-1-1z"></path>
        </symbol>
        <symbol id="icon-user" viewBox="0 0 32 32">
            <!-- <title>person</title> -->
            <path d="M16 18.688c3.563 0 10.688 1.75 10.688 5.313v2.688h-21.375v-2.688c0-3.563 7.125-5.313 10.688-5.313zM16 16c-2.938 0-5.313-2.375-5.313-5.313s2.375-5.375 5.313-5.375 5.313 2.438 5.313 5.375-2.375 5.313-5.313 5.313z"></path>
        </symbol>
        <symbol id="icon-device" viewBox="0 0 32 32">
            <!-- <title>device</title> -->
            <path d="M 18.060541,2.0461144 1.9645265,12.44571 2.0034027,13.48277 16.817103,19.713445 17.248421,19.665439 32.215116,7.6225947 32.23142,6.8258092 31.625754,6.2292635 19.164948,2.0479158 c -0.127529,-1.775e-4 -0.657029,-8.874e-4 -1.104414,-0.00266 z m 14.023267,6.7084964 -14.847629,11.9899932 -0.398512,0.02742 -14.7394919,-6.378638 0.023076,5.97283 c 0.074472,0.08969 0.455743,0.529648 0.526693,0.612962 l 13.5117559,6.121658 0.825578,-0.03088 14.729774,-12.28646 0.359026,-0.63118 z M 3.0422101,15.333109 6.3919049,16.701161 v 0.919128 l -3.3496948,-1.368061 -0.00981,-0.277542 2.8137021,1.179093 0.00981,-0.526284 -2.8162961,-1.199846 z m 4.893323,1.880778 0.7561734,0.289213 v 1.258893 l 2.5935155,1.187593 v 0.983632 L 8.6917065,19.719501 v 1.406664 l 2.5935155,1.213105 v 0.04375 L 7.9355331,20.876439 Z m 4.6096209,1.920265 0.756176,0.289222 v 1.259506 l 2.594122,1.187584 v 0.983019 L 13.30133,21.639775 v 1.406664 l 2.594122,1.213709 v 0.04375 L 12.545154,22.79732 Z"></path>
        </symbol>
        <symbol id="icon-menu" viewBox="0 0 32 32">
            <!-- <title>menu</title> -->
            <path id="icon-menu-top" d="m 27.93924,5.3202643 v 2.65165 H 4.2497483 v -2.65165 z"></path>
            <path id="icon-menu-middle" d="m 27.93924,14.202737 v 2.65165 H 4.2497483 v -2.65165 z"></path>
            <path id="icon-menu-bottom" d="m 27.93924,23.085145 v 2.65165 H 4.2497483 v -2.65165 z"></path>
        </symbol>
        <symbol id="icon-apps" viewBox="0 0 32 32">
            <!-- <title>apps</title> -->
            <path d="m 6.8832443,0.32091057 c -1.5502497,0 -2.8314932,1.34817863 -2.8314932,2.89950673 V 29.111936 c 0,1.551328 1.2801643,2.900061 2.8314932,2.900061 H 21.312206 c 1.550249,0 2.832052,-1.348733 2.832052,-2.900061 V 3.2204173 c 0,-1.5513281 -1.280725,-2.89950673 -2.832052,-2.89950673 z m 0,4.31497453 H 21.312206 v 9.1634669 l -0.857976,-0.857421 -6.144658,6.917339 -3.459227,-3.499366 -3.9671007,3.9671 z M 21.312206,14.571476 V 27.695353 H 6.8832443 v -4.76431 l 3.9671007,-3.967102 3.459227,3.499366 z"></path>
        </symbol>
        <symbol id="icon-tasks" viewBox="0 0 32 32">
            <!-- <title>tasks</title> -->
            <path d="M18.286 25.143h11.429v-2.286h-11.429v2.286zM11.429 16h18.286v-2.286h-18.286v2.286zM22.857 6.857h6.857v-2.286h-6.857v2.286zM32 21.714v4.571c0 0.625-0.518 1.143-1.143 1.143h-29.714c-0.625 0-1.143-0.518-1.143-1.143v-4.571c0-0.625 0.518-1.143 1.143-1.143h29.714c0.625 0 1.143 0.518 1.143 1.143zM32 12.571v4.571c0 0.625-0.518 1.143-1.143 1.143h-29.714c-0.625 0-1.143-0.518-1.143-1.143v-4.571c0-0.625 0.518-1.143 1.143-1.143h29.714c0.625 0 1.143 0.518 1.143 1.143zM32 3.429v4.571c0 0.625-0.518 1.143-1.143 1.143h-29.714c-0.625 0-1.143-0.518-1.143-1.143v-4.571c0-0.625 0.518-1.143 1.143-1.143h29.714c0.625 0 1.143 0.518 1.143 1.143z"></path>
        </symbol>
        <symbol id="icon-logout" viewBox="0 0 32 32">
            <!-- <title>logout</title> -->
            <path d="M23.75 6.875c2.563 2.188 4.25 5.5 4.25 9.125 0 6.625-5.375 12-12 12s-12-5.375-12-12c0-3.625 1.688-6.938 4.25-9.125l1.875 1.875c-2.063 1.688-3.438 4.313-3.438 7.25 0 5.188 4.125 9.313 9.313 9.313s9.313-4.125 9.313-9.313c0-2.938-1.313-5.5-3.438-7.188zM17.313 4v13.313h-2.625v-13.313h2.625z"></path>
        </symbol>
        <symbol id="icon-expand" viewBox="0 0 32 32">
            <!-- <title>expand</title> -->
            <path d="M32 0v13l-5-5-6 6-3-3 6-6-5-5zM14 21l-6 6 5 5h-13v-13l5 5 6-6z"></path>
        </symbol>
        <symbol id="icon-contract" viewBox="0 0 32 32">
            <!-- <title>contract</title> -->
            <path d="M14 18v13l-5-5-6 6-3-3 6-6-5-5zM32 3l-6 6 5 5h-13v-13l5 5 6-6z"></path>
        </symbol>
        <symbol id="icon-favorite" viewBox="0 0 32 32">
            <!-- <title>favorite</title> -->
            <path d="M16 28.438l-1.938-1.75c-6.875-6.25-11.375-10.313-11.375-15.375 0-4.125 3.188-7.313 7.313-7.313 2.313 0 4.563 1.125 6 2.813 1.438-1.688 3.688-2.813 6-2.813 4.125 0 7.313 3.188 7.313 7.313 0 5.063-4.5 9.188-11.375 15.438z"></path>
        </symbol>
        <symbol id="icon-cydynni" viewBox="0 0 32 32">
            <!-- <title>cydynni</title> -->
            <path d="m 22.051367,2.692342 c -0.572053,0.2919087 0.219921,0.7687035 0.49506,0.9596313 2.35741,1.722664 4.17818,4.3403366 4.391463,7.3111817 0.354926,2.472887 -0.404392,4.993486 -1.906343,6.977869 -1.451917,2.14211 -3.769212,3.652201 -6.337598,4.033862 -0.972948,0.196311 -1.982065,0.253178 -2.957442,0.294039 -0.486073,0.232051 -0.07716,0.802016 0.336414,0.841809 2.85142,1.263645 6.176712,0.790306 8.933304,-0.492993 3.366355,-1.798339 5.778493,-5.46159 5.652884,-9.335348 C 30.694431,10.342144 29.520962,7.3948964 27.319263,5.4151733 25.864275,4.0298764 24.019673,3.0659855 22.051367,2.692342 Z M 7.2057616,2.9207518 C 5.9519983,3.4272018 5.1198983,4.6416928 4.2142129,5.6043091 -0.20107634,10.708906 -0.2246082,18.872267 3.9144897,24.145296 c 2.9296453,3.851015 7.7580893,6.284978 12.6307533,5.943305 2.768021,-0.01093 5.463677,-0.930232 7.750431,-2.467033 1.786018,-1.102177 3.412559,-2.591182 4.227131,-4.567161 -1.79162,0.88555 -3.257594,2.342916 -5.197097,2.944523 C 18.376334,27.855349 12.392154,26.879208 8.6005084,23.120035 4.8217245,19.665294 3.0410409,14.007825 4.6467447,9.0764486 5.2303761,7.0426566 6.2778295,5.1454179 7.5747313,3.4814412 7.6713018,3.2289345 7.4881046,2.9159425 7.2057616,2.9207518 Z m 8.1684934,2.2215699 c -0.144492,-0.003 -0.289136,-0.00209 -0.434082,0.0031 -3.722382,0.06041 -7.0671306,3.0163333 -7.715291,6.6605753 -0.5340067,2.437721 0.1792063,5.151894 1.9332151,6.932393 0.5863564,0.208494 0.7140073,-0.626811 0.3772379,-0.958081 -1.5253882,-3.558474 0.624852,-8.2591008 4.467428,-9.1456947 3.491227,-1.1447904 7.518075,1.268508 8.396386,4.7759357 0.167385,0.781284 0.34081,1.57074 0.797884,2.245858 0.762035,-2.778632 0.06801,-5.9515091 -2.032951,-7.9772909 C 19.6409,6.1426494 17.541642,5.1872657 15.374255,5.1423217 Z"></path>
        </symbol>
        <symbol id="icon-earth" viewBox="0 0 32 32">
            <!-- <title>earth</title> -->
            <path d="M16 0c-8.837 0-16 7.163-16 16s7.163 16 16 16 16-7.163 16-16-7.163-16-16-16zM16 30c-1.967 0-3.84-0.407-5.538-1.139l7.286-8.197c0.163-0.183 0.253-0.419 0.253-0.664v-3c0-0.552-0.448-1-1-1-3.531 0-7.256-3.671-7.293-3.707-0.188-0.188-0.442-0.293-0.707-0.293h-4c-0.552 0-1 0.448-1 1v6c0 0.379 0.214 0.725 0.553 0.894l3.447 1.724v5.871c-3.627-2.53-6-6.732-6-11.489 0-2.147 0.484-4.181 1.348-6h3.652c0.265 0 0.52-0.105 0.707-0.293l4-4c0.188-0.188 0.293-0.442 0.293-0.707v-2.419c1.268-0.377 2.61-0.581 4-0.581 2.2 0 4.281 0.508 6.134 1.412-0.13 0.109-0.256 0.224-0.376 0.345-1.133 1.133-1.757 2.64-1.757 4.243s0.624 3.109 1.757 4.243c1.139 1.139 2.663 1.758 4.239 1.758 0.099 0 0.198-0.002 0.297-0.007 0.432 1.619 1.211 5.833-0.263 11.635-0.014 0.055-0.022 0.109-0.026 0.163-2.541 2.596-6.084 4.208-10.004 4.208z"></path>
        </symbol>
        <symbol id="icon-schedule" viewBox="0 0 32 32">
            <!-- <title>schedule</title> -->
            <path d="M16.688 9.313v7l6 3.563-1 1.688-7-4.25v-8h2zM16 26.688c5.875 0 10.688-4.813 10.688-10.688s-4.813-10.688-10.688-10.688-10.688 4.813-10.688 10.688 4.813 10.688 10.688 10.688zM16 2.688c7.375 0 13.313 5.938 13.313 13.313s-5.938 13.313-13.313 13.313-13.313-5.938-13.313-13.313 5.938-13.313 13.313-13.313z"></path>
        </symbol>
        <symbol id="icon-present_to_all" viewBox="0 0 32 32">
            <!-- <title>present_to_all</title> -->
            <path d="M13.313 16h-2.625l5.313-5.313 5.313 5.313h-2.625v5.313h-5.375v-5.313zM28 25.375v-18.75h-24v18.75h24zM28 4c1.5 0 2.688 1.188 2.688 2.688v18.625c0 1.5-1.188 2.688-2.688 2.688h-24c-1.5 0-2.688-1.188-2.688-2.688v-18.625c0-1.5 1.188-2.688 2.688-2.688h24z"></path>
        </symbol>
        <symbol id="icon-folder-plus" viewBox="0 0 32 32">
            <!-- <title>folder-plus</title> -->
            <path d="M18 8l-4-4h-14v26h32v-22h-14zM22 22h-4v4h-4v-4h-4v-4h4v-4h4v4h4v4z"></path>
        </symbol>
        <symbol id="icon-close" viewBox="0 0 32 32">
            <!-- <title>close</title> -->
            <path d="M25.313 8.563l-7.438 7.438 7.438 7.438-1.875 1.875-7.438-7.438-7.438 7.438-1.875-1.875 7.438-7.438-7.438-7.438 1.875-1.875 7.438 7.438 7.438-7.438z"></path>
        </symbol>
        <symbol id="icon-search" viewBox="0 0 32 32">
            <!-- <title>search</title> -->
            <path d="M31.008 27.231l-7.58-6.447c-0.784-0.705-1.622-1.029-2.299-0.998 1.789-2.096 2.87-4.815 2.87-7.787 0-6.627-5.373-12-12-12s-12 5.373-12 12 5.373 12 12 12c2.972 0 5.691-1.081 7.787-2.87-0.031 0.677 0.293 1.515 0.998 2.299l6.447 7.58c1.104 1.226 2.907 1.33 4.007 0.23s0.997-2.903-0.23-4.007zM12 20c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z"></path>
        </symbol>
        <symbol id="icon-shuffle" viewBox="0 0 32 32">
            <!-- <title>shuffle</title> -->
            <path d="M24 22h-3.172l-5-5 5-5h3.172v5l7-7-7-7v5h-4c-0.53 0-1.039 0.211-1.414 0.586l-5.586 5.586-5.586-5.586c-0.375-0.375-0.884-0.586-1.414-0.586h-6v4h5.172l5 5-5 5h-5.172v4h6c0.53 0 1.039-0.211 1.414-0.586l5.586-5.586 5.586 5.586c0.375 0.375 0.884 0.586 1.414 0.586h4v5l7-7-7-7v5z"></path>
        </symbol>
        <symbol id="icon-arrow_back" viewBox="0 0 32 32">
            <!-- <title>arrow_back</title> -->
            <path d="M26.688 14.688v2.625h-16.25l7.438 7.5-1.875 1.875-10.688-10.688 10.688-10.688 1.875 1.875-7.438 7.5h16.25z"></path>
        </symbol>
        <symbol id="icon-calendar" viewBox="0 0 32 32">
            <!-- <title>calendar</title> -->
            <path d="M10 12h4v4h-4zM16 12h4v4h-4zM22 12h4v4h-4zM4 24h4v4h-4zM10 24h4v4h-4zM16 24h4v4h-4zM10 18h4v4h-4zM16 18h4v4h-4zM22 18h4v4h-4zM4 18h4v4h-4zM26 0v2h-4v-2h-14v2h-4v-2h-4v32h30v-32h-4zM28 30h-26v-22h26v22z"></path>
        </symbol>
        <symbol id="icon-spinner11" viewBox="0 0 32 32">
            <!-- <title>spinner11</title> -->
            <path d="M32 12h-12l4.485-4.485c-2.267-2.266-5.28-3.515-8.485-3.515s-6.219 1.248-8.485 3.515c-2.266 2.267-3.515 5.28-3.515 8.485s1.248 6.219 3.515 8.485c2.267 2.266 5.28 3.515 8.485 3.515s6.219-1.248 8.485-3.515c0.189-0.189 0.371-0.384 0.546-0.583l3.010 2.634c-2.933 3.349-7.239 5.464-12.041 5.464-8.837 0-16-7.163-16-16s7.163-16 16-16c4.418 0 8.418 1.791 11.313 4.687l4.687-4.687v12z"></path>
        </symbol>
        <symbol id="icon-users" viewBox="0 0 36 32">
            <!-- <title>users</title> -->
            <path d="M24 24.082v-1.649c2.203-1.241 4-4.337 4-7.432 0-4.971 0-9-6-9s-6 4.029-6 9c0 3.096 1.797 6.191 4 7.432v1.649c-6.784 0.555-12 3.888-12 7.918h28c0-4.030-5.216-7.364-12-7.918z"></path>
            <path d="M10.225 24.854c1.728-1.13 3.877-1.989 6.243-2.513-0.47-0.556-0.897-1.176-1.265-1.844-0.95-1.726-1.453-3.627-1.453-5.497 0-2.689 0-5.228 0.956-7.305 0.928-2.016 2.598-3.265 4.976-3.734-0.529-2.39-1.936-3.961-5.682-3.961-6 0-6 4.029-6 9 0 3.096 1.797 6.191 4 7.432v1.649c-6.784 0.555-12 3.888-12 7.918h8.719c0.454-0.403 0.956-0.787 1.506-1.146z"></path>
        </symbol>
        <symbol id="icon-cogs" viewBox="0 0 32 32">
            <!-- <title>cogs</title> -->
            <path d="M11.366 22.564l1.291-1.807-1.414-1.414-1.807 1.291c-0.335-0.187-0.694-0.337-1.071-0.444l-0.365-2.19h-2l-0.365 2.19c-0.377 0.107-0.736 0.256-1.071 0.444l-1.807-1.291-1.414 1.414 1.291 1.807c-0.187 0.335-0.337 0.694-0.443 1.071l-2.19 0.365v2l2.19 0.365c0.107 0.377 0.256 0.736 0.444 1.071l-1.291 1.807 1.414 1.414 1.807-1.291c0.335 0.187 0.694 0.337 1.071 0.444l0.365 2.19h2l0.365-2.19c0.377-0.107 0.736-0.256 1.071-0.444l1.807 1.291 1.414-1.414-1.291-1.807c0.187-0.335 0.337-0.694 0.444-1.071l2.19-0.365v-2l-2.19-0.365c-0.107-0.377-0.256-0.736-0.444-1.071zM7 27c-1.105 0-2-0.895-2-2s0.895-2 2-2 2 0.895 2 2-0.895 2-2 2zM32 12v-2l-2.106-0.383c-0.039-0.251-0.088-0.499-0.148-0.743l1.799-1.159-0.765-1.848-2.092 0.452c-0.132-0.216-0.273-0.426-0.422-0.629l1.219-1.761-1.414-1.414-1.761 1.219c-0.203-0.149-0.413-0.29-0.629-0.422l0.452-2.092-1.848-0.765-1.159 1.799c-0.244-0.059-0.492-0.109-0.743-0.148l-0.383-2.106h-2l-0.383 2.106c-0.251 0.039-0.499 0.088-0.743 0.148l-1.159-1.799-1.848 0.765 0.452 2.092c-0.216 0.132-0.426 0.273-0.629 0.422l-1.761-1.219-1.414 1.414 1.219 1.761c-0.149 0.203-0.29 0.413-0.422 0.629l-2.092-0.452-0.765 1.848 1.799 1.159c-0.059 0.244-0.109 0.492-0.148 0.743l-2.106 0.383v2l2.106 0.383c0.039 0.251 0.088 0.499 0.148 0.743l-1.799 1.159 0.765 1.848 2.092-0.452c0.132 0.216 0.273 0.426 0.422 0.629l-1.219 1.761 1.414 1.414 1.761-1.219c0.203 0.149 0.413 0.29 0.629 0.422l-0.452 2.092 1.848 0.765 1.159-1.799c0.244 0.059 0.492 0.109 0.743 0.148l0.383 2.106h2l0.383-2.106c0.251-0.039 0.499-0.088 0.743-0.148l1.159 1.799 1.848-0.765-0.452-2.092c0.216-0.132 0.426-0.273 0.629-0.422l1.761 1.219 1.414-1.414-1.219-1.761c0.149-0.203 0.29-0.413 0.422-0.629l2.092 0.452 0.765-1.848-1.799-1.159c0.059-0.244 0.109-0.492 0.148-0.743l2.106-0.383zM21 15.35c-2.402 0-4.35-1.948-4.35-4.35s1.948-4.35 4.35-4.35 4.35 1.948 4.35 4.35c0 2.402-1.948 4.35-4.35 4.35z"></path>
        </symbol>
        <symbol id="icon-box-add" viewBox="0 0 32 32">
            <!-- <title>box-add</title> -->
            <path d="M26 2h-20l-6 6v21c0 0.552 0.448 1 1 1h30c0.552 0 1-0.448 1-1v-21l-6-6zM16 26l-10-8h6v-6h8v6h6l-10 8zM4.828 6l2-2h18.343l2 2h-22.343z"></path>
        </symbol>
        <symbol id="icon-cog" viewBox="0 0 32 32">
            <!-- <title>cog</title> -->
            <path d="M29.181 19.070c-1.679-2.908-0.669-6.634 2.255-8.328l-3.145-5.447c-0.898 0.527-1.943 0.829-3.058 0.829-3.361 0-6.085-2.742-6.085-6.125h-6.289c0.008 1.044-0.252 2.103-0.811 3.070-1.679 2.908-5.411 3.897-8.339 2.211l-3.144 5.447c0.905 0.515 1.689 1.268 2.246 2.234 1.676 2.903 0.672 6.623-2.241 8.319l3.145 5.447c0.895-0.522 1.935-0.82 3.044-0.82 3.35 0 6.067 2.725 6.084 6.092h6.289c-0.003-1.034 0.259-2.080 0.811-3.038 1.676-2.903 5.399-3.894 8.325-2.219l3.145-5.447c-0.899-0.515-1.678-1.266-2.232-2.226zM16 22.479c-3.578 0-6.479-2.901-6.479-6.479s2.901-6.479 6.479-6.479c3.578 0 6.479 2.901 6.479 6.479s-2.901 6.479-6.479 6.479z"></path>
        </symbol>
        <symbol id="icon-star" viewBox="0 0 32 32">
            <!-- <title>star</title> -->
            <path d="M16 23l-8.25 5 2.188-9.375-7.25-6.313 9.563-0.813 3.75-8.813 3.75 8.813 9.563 0.813-7.25 6.313 2.188 9.375z"></path>
        </symbol>
        <symbol id="icon-star_border" viewBox="0 0 32 32">
            <!-- <title>star_border</title> -->
            <path d="M16 20.563l5 3-1.313-5.688 4.438-3.875-5.875-0.5-2.25-5.375-2.25 5.375-5.875 0.5 4.438 3.875-1.313 5.688zM29.313 12.313l-7.25 6.313 2.188 9.375-8.25-5-8.25 5 2.188-9.375-7.25-6.313 9.563-0.813 3.75-8.813 3.75 8.813z"></path>
        </symbol>
        <symbol id="icon-bin" viewBox="0 0 32 32">
            <!-- <title>bin</title> -->
            <path d="M4 10v20c0 1.1 0.9 2 2 2h18c1.1 0 2-0.9 2-2v-20h-22zM10 28h-2v-14h2v14zM14 28h-2v-14h2v14zM18 28h-2v-14h2v14zM22 28h-2v-14h2v14z"></path>
            <path d="M26.5 4h-6.5v-2.5c0-0.825-0.675-1.5-1.5-1.5h-7c-0.825 0-1.5 0.675-1.5 1.5v2.5h-6.5c-0.825 0-1.5 0.675-1.5 1.5v2.5h26v-2.5c0-0.825-0.675-1.5-1.5-1.5zM18 4h-6v-1.975h6v1.975z"></path>
        </symbol>
        <symbol id="icon-wifi" viewBox="0 0 32 32">
            <!-- <title>wifi</title> -->
            <path d="M6.688 17.313c5.188-5.125 13.5-5.125 18.625 0l-2.625 2.688c-3.688-3.688-9.688-3.688-13.375 0zM12 22.688c2.188-2.188 5.813-2.188 8 0l-4 4zM1.313 12c8.125-8.063 21.313-8.063 29.375 0l-2.688 2.688c-6.625-6.625-17.375-6.625-24 0z"></path>
        </symbol>
        <symbol id="icon-arrow_forward" viewBox="0 0 32 32">
            <!-- <title>arrow_forward</title> -->
            <path d="M16 5.313l10.688 10.688-10.688 10.688-1.875-1.875 7.438-7.5h-16.25v-2.625h16.25l-7.438-7.5z"></path>
        </symbol>
        <symbol id="icon-enter" viewBox="0 0 32 32">
            <!-- <title>enter</title> -->
            <path d="M12 16h-10v-4h10v-4l6 6-6 6zM32 0v26l-12 6v-6h-12v-8h2v6h10v-18l8-4h-18v8h-2v-10z"></path>
        </symbol>
        <symbol id="icon-content_copy" viewBox="0 0 32 32">
            <!-- <title>content_copy</title> -->
            <path d="M25.313 28v-18.688h-14.625v18.688h14.625zM25.313 6.688c1.438 0 2.688 1.188 2.688 2.625v18.688c0 1.438-1.25 2.688-2.688 2.688h-14.625c-1.438 0-2.688-1.25-2.688-2.688v-18.688c0-1.438 1.25-2.625 2.688-2.625h14.625zM21.313 1.313v2.688h-16v18.688h-2.625v-18.688c0-1.438 1.188-2.688 2.625-2.688h16z"></path>
        </symbol>
    </defs>
</svg>
  </body>
</html>