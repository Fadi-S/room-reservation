import { Inertia } from "@inertiajs/inertia";

export default function useUser() {
    return Inertia.page.props?.auth?.user;
}
