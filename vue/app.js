import Vue from 'vue';
import Swal from 'sweetalert2';
import LaravelEcho from "laravel-echo"

window.Vue = Vue;
window.Swal = Swal;
window.LaravelEcho = LaravelEcho;

window.Pusher = require('pusher-js');

require('./js/bootstrap')
require('./js/validator')
require('./js/lodash')


