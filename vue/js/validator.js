import Vue from 'vue'
import VeeValidate from 'vee-validate'

window.Validator = VeeValidate.Validator

Vue.use(VeeValidate, {
  events: 'input|blur'
})
