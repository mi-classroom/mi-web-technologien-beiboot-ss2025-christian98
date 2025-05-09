declare namespace App.Data {
export type BreadcrumbData = {
name: string;
url: string;
};
}
declare namespace App.Services.Image.Exif {
export type ExifGroup = 'FILE' | 'COMPUTED' | 'IFD0' | 'COMMENT' | 'EXIF';
export type ExifTag = {
name: string;
value: string;
};
}
declare namespace App.Services.Image.Exif.File {
export type ExifFileTag = 'FileName' | 'FileDateTime' | 'FileSize' | 'FileType' | 'MimeType' | 'SectionsFound';
}
declare namespace App.Services.Image.IPTC {
export type IptcTag = 90 | 116 | 101 | 100 | 80 | 85 | 110 | 55 | 120 | 122 | 105 | 40 | 4 | 103 | 25 | 95 | 115 | 12 | 92 | 60 | 5 | 0 | 1 | 123;
}
declare namespace App.Services.Image.ImageType {
export type ImageType = 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 | 11 | 12 | 13 | 14 | 15 | 16 | 17 | 18 | 19 | 20 | 0;
}
