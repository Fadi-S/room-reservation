import { usePage } from "@inertiajs/vue3";

export default function useUser() {
    return usePage().props?.auth?.user;
}
