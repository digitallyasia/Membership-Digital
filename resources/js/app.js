import Vue from "vue";
import VueMeta from "vue-meta";
import PortalVue from "portal-vue";
import { app,plugin } from "@inertiajs/inertia-vue";

Vue.config.productionTip = false;
Vue.mixin({ methods: { route: window.route } });
Vue.use(plugin);
Vue.use(PortalVue);
Vue.use(VueMeta);
Vue.config.ignoredElements = ["trix-editor"];

var moment = require("moment");
Vue.mixin({
    methods: {
        moment(...args) {
            return moment.utc(...args).local();
        }
    }
});

let el = document.getElementById("app");
new Vue({
    metaInfo: {
        titleTemplate: title =>
            title ? `${title} - Membership Digital` : "Membership Digital"
    },
    render: h =>
        h(app, {
            props: {
                initialPage: JSON.parse(el.dataset.page),
                resolveComponent: name => require(`./Pages/${name}`).default
            }
        })
}).$mount(el);
