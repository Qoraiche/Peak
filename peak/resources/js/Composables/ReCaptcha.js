export function useRecaptcha() {
    const existingScript = document.getElementById("recaptcha");
    if (existingScript) {
        existingScript.remove();
    }

    let recaptchaScript = document.createElement("script");
    recaptchaScript.setAttribute("id", "recaptcha");
    recaptchaScript.setAttribute("src", "https://www.google.com/recaptcha/api.js");
    recaptchaScript.setAttribute("async", "true");
    recaptchaScript.setAttribute("defer", "true");

    recaptchaScript.onload = () => {
        console.log("reCAPTCHA script loaded.");
        if (typeof grecaptcha !== "undefined") {
            grecaptcha.reset();
        }
    };

    document.head.appendChild(recaptchaScript);
}
