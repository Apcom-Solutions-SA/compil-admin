// import Vue from 'vue'
// import SvgIcon from '@/components/SvgIcon'// svg component

// register globally
// Vue.component('svg-icon', SvgIcon)

// automatically register svg files from  svg folder  
const req = require.context('./svg', false, /\.svg$/); // function webpackContext(req){...}  
const requireAll = requireContext => requireContext.keys().map(requireContext)
requireAll(req)