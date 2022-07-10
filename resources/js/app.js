import "./bootstrap";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import moment from "moment";

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        const App = createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route } });

        App.config.globalProperties.window = window;

        App.config.globalProperties.formatDate = (
            value,
            defaultValue = null
        ) => {
            return value
                ? moment(String(value)).format("DD MMMM YYYY")
                : defaultValue;
        };

        // App.config.globalProperties.mapRoleName = (roleName) => {
        //     console.log(roleName);
        //     if (roleName === "company") return "school";
        //     return roleName;
        // };

        return App.mount(el);
    },
});

InertiaProgress.init({ color: "#4B5563" });
