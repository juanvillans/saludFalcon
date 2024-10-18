import { writable } from "svelte/store";

export const navStatus = writable({
    isContracted: true,
    navWidth: 60,
    
});

export function toggleMenu(objParams) {
    navStatus.update((current) => {
        let newStatus = !current.isContracted 
        if (newStatus == true) {
             return { ...current, isContracted: newStatus, navWidth: 60 };
        } else {
            return { ...current, isContracted: newStatus, navWidth: 210 };

        }
        console.log({current})
    });
}
