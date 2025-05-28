import Swal from "sweetalert2";
import {__} from "@peak/Composables/useTranslate.js";

/**
 * Reusable confirmation modal function
 * @param {Object} options - Configuration for the modal
 * @returns {Promise<boolean>} - Resolves to true if confirmed, false otherwise
 */
export function useConfirm(options = {}) {
    const defaultOptions = {
        title: __("Are you sure?"),
        text: __("This action cannot be undone."),
        confirmButtonText: __("Confirm"),
        cancelButtonText: __("Cancel"),
        confirmButtonColor: "tomato",
        icon: "warning",
        showCancelButton: true
    };

    return Swal.fire({...defaultOptions, ...options}).then((result) => {
        return result.isConfirmed; // Returns true if confirmed, false otherwise
    });
}
