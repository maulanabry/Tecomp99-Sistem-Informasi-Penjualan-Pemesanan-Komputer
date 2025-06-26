import "./bootstrap";
import "flowbite";
import { Livewire } from "../../vendor/livewire/livewire/dist/livewire.esm";
import Alpine from "alpinejs";

// Initialize Flowbite on page load
document.addEventListener("DOMContentLoaded", () => {
    initFlowbite();
});

// Re-initialize Flowbite after Livewire navigation
document.addEventListener("livewire:navigated", () => {
    console.log("Navigated");
    initFlowbite();
});

// Re-initialize Flowbite after Livewire updates
document.addEventListener("livewire:load", () => {
    initFlowbite();
});

// Re-initialize Flowbite after Livewire component updates
Livewire.hook("morph.updated", ({ el, component }) => {
    initFlowbite();
});
