import Vue from "vue";
import VueMeta from "vue-meta";
import PortalVue from "portal-vue";
import { InertiaApp } from "@inertiajs/inertia-vue";

Vue.config.productionTip = false;
Vue.mixin({ methods: { route: window.route } });
Vue.use(InertiaApp);
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

let app = document.getElementById("app");
new Vue({
    metaInfo: {
        titleTemplate: title =>
            title ? `${title} - Membership Digital` : "Membership Digital"
    },
    render: h =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: name =>
                    import(`@/Pages/${name}`).then(module => module.default)
            }
        })
}).$mount(app);
