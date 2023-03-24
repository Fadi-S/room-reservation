import { createApp, h } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import "../css/app.css";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

createInertiaApp({
    progress: {
        delay: 300,
        color: "#99de87",
        showSpinner: true,
    },
    async resolve(name) {
        const page = await resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );

        page.default.layout ??= Layout;

        return page;
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .component("InertiaLink", Link)
            .component("Head", Head)
            .mount(el);
    },

    title: (title) => `${title} - StGeorge Sporting`,
});
