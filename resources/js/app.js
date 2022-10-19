import { createApp, h } from "vue";
import { createInertiaApp, Head, InertiaLink } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layout.vue";
import { InertiaProgress } from "@inertiajs/progress";
import "../css/app.css";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

createInertiaApp({
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
            .component("InertiaLink", InertiaLink)
            .component("Head", Head)
            .mount(el);
    },

    title: (title) => `${title} - StGeorge Sporting`,
});

InertiaProgress.init({
    delay: 300,
    color: "#99de87",
    showSpinner: true,
});
