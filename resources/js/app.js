import "./bootstrap";
import "flowbite";
import { Livewire } from "../../vendor/livewire/livewire/dist/livewire.esm";
import Alpine from "alpinejs";

document.addEventListener("livewire:navigated", () => {
    console.log("Navigated");
    initFlowbite();
});
