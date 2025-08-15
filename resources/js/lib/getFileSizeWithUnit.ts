const units = ["Byte", "KB", "MB", "GB", "TB", "PB"];

export function getFileSizeWithUnit(
    size: number,
    floating: number = 1
): [number, string] | [] {
    for (let i = 0; i < units.length; i += 1) {
        if (size < 1024 ** (i + 1)) {
            const floatingBase = 10 ** floating;
            const num =
                Math.ceil((size * floatingBase) / Math.max(1, 1024 ** i)) /
                floatingBase;
            return [num, units[i]];
        }
    }

    console.error("[getFileSizeWithUnit]: too large file size");
    return [];
}
