import { clsx } from "clsx";
import moment from "moment";
import { twMerge } from "tailwind-merge";


export function cn(...inputs) {
    return twMerge(clsx(inputs));
}

export function formatDate(date) {
    return moment(date).format("DD-MM-YYYY");
}
