import "./bootstrap";
import "flowbite";
import Alpine from "alpinejs";
import { Livewire } from "../../vendor/livewire/livewire/dist/livewire.esm";
import { initFlowbite } from "flowbite";

window.Alpine = Alpine;

Alpine.start();
Livewire.start();

document.addEventListener("livewire:navigated", () => {
    // Reinitialize Flowbite components
    initFlowbite();
    Livewire.start();
});
