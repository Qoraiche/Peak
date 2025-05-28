export function humanFileSize(bytes, si = false, dp = 1) {
    const thresh = si ? 1000 : 1024;

    if (Math.abs(bytes) < thresh) {
        return bytes + " B";
    }

    const units = si ? ["kB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"] : ["KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB"];
    let u = -1;
    const r = 10 ** dp;

    do {
        bytes /= thresh;
        ++u;
    } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);

    return bytes.toFixed(dp) + " " + units[u];
}

export function slugify(str) {
    // 1️⃣ Handle null, undefined, or empty strings
    if (!str) return "";

    // 2️⃣ Normalize and remove accents (handles non-Latin and accented characters)
    let slug = str
        .normalize("NFD") // Decompose accented characters
        .replace(/[\u0300-\u036f]/g, "") // Remove diacritical marks

        // 3️⃣ Allow letters, numbers, and spaces/dashes for **ALL scripts (Arabic, Japanese, etc.)**
        .replace(/[^a-zA-Z0-9\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\uFB50-\uFDFF\uFE70-\uFEFF\u0400-\u04FF\u2E80-\u2EFF\u3040-\u309F\u30A0-\u30FF\u4E00-\u9FFF\s-]/g, "")

        // 4️⃣ Trim spaces, lowercase, and convert spaces to dashes
        .trim()
        .toLowerCase()
        .replace(/\s+/g, "-") // Replace spaces with hyphens
        .replace(/-+/g, "-"); // Remove consecutive hyphens

    // 5️⃣ Truncate the slug to a maximum of 100 characters
    return slug.slice(0, 100);
}

// Function to extract headings and remove them from content
export function extractHeadingsFromEditorJs(content) {

    if (!Array.isArray(content) || content.length === 0) {
        // If content is empty or not an array, return empty headings and the content as is
        return {
            updatedContent: content, headings: [],
        };
    }

    const headings = [];

    const updatedContent = content.filter(block => {
        if (block.type === 'header') {
            // Extract heading info (you might want to adjust this based on your header structure)
            headings.push({
                text: block.data.text.trim(),  // Assuming id is available in the block
                tag: `h${block.data.level}`, // Heading level (h1, h2, etc.)
                anchor: block.data.anchor || null
            });

            return false; // Removing this heading block from the content
        }
        return true; // Keep non-heading blocks
    });

    // Return the modified content and the extracted headings
    return {
        updatedContent, headings,
    };
}


export function scrollTo(anchor) {
    const element = document.querySelector(anchor);

    if (element) {
        element.scrollIntoView({
            behavior: "smooth",
        });
    }
}

export function isValidDomain(domain) {
    // Regular expression to validate domain names
    const domainRegex = /([a-zA-Z0-9])([a-zA-Z0-9-]{1,61}[a-zA-Z0-9])(?:\.([a-zA-Z]{2,}))+/;

    return domainRegex.test(domain);
}

export function limitText(text, maxLength = 200) {
    if (text.length > maxLength) {
        return text.slice(0, maxLength) + "...";
    }
    return text;
}

export function getDomain(domain) {
    const regexMe = /([a-zA-Z0-9])([a-zA-Z0-9-]{1,61}[a-zA-Z0-9])(?:\.([a-zA-Z]{2,}))+/;

    const match = regexMe.exec(domain);

    if (match) {
        return match[0];
    }
}
