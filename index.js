const {
  Document,
  Packer,
  Paragraph,
  TextRun,
  Table,
  TableRow,
  TableCell,
  Header,
  Footer,
  AlignmentType,
  HeadingLevel,
  BorderStyle,
  WidthType,
  ShadingType,
  VerticalAlign,
  PageNumber,
  PageBreak,
  LevelFormat,
  TableOfContents,
} = require("docx");
const fs = require("fs");

// Colors
const BLUE_DARK = "1F4E79";
const BLUE_MID = "2E75B6";
const BLUE_LIGHT = "D6E4F0";
const BLUE_HEADER = "2E75B6";
const ACCENT = "E2EFDA";
const GRAY_LIGHT = "F2F2F2";
const WHITE = "FFFFFF";

const border = { style: BorderStyle.SINGLE, size: 1, color: "AAAAAA" };
const borders = { top: border, bottom: border, left: border, right: border };
const noBorder = { style: BorderStyle.NONE, size: 0, color: "FFFFFF" };
const noBorders = {
  top: noBorder,
  bottom: noBorder,
  left: noBorder,
  right: noBorder,
};

function h1(text) {
  return new Paragraph({
    heading: HeadingLevel.HEADING_1,
    children: [
      new TextRun({ text, bold: true, size: 32, font: "Arial", color: WHITE }),
    ],
    shading: { fill: BLUE_DARK, type: ShadingType.CLEAR },
    spacing: { before: 360, after: 200 },
    indent: { left: 200, right: 200 },
  });
}

function h2(text) {
  return new Paragraph({
    heading: HeadingLevel.HEADING_2,
    children: [
      new TextRun({ text, bold: true, size: 26, font: "Arial", color: WHITE }),
    ],
    shading: { fill: BLUE_MID, type: ShadingType.CLEAR },
    spacing: { before: 280, after: 140 },
    indent: { left: 200 },
  });
}

function h3(text) {
  return new Paragraph({
    heading: HeadingLevel.HEADING_3,
    children: [
      new TextRun({
        text,
        bold: true,
        size: 24,
        font: "Arial",
        color: BLUE_DARK,
      }),
    ],
    spacing: { before: 200, after: 100 },
    border: {
      bottom: { style: BorderStyle.SINGLE, size: 4, color: BLUE_MID, space: 2 },
    },
  });
}

function h4(text) {
  return new Paragraph({
    children: [
      new TextRun({
        text,
        bold: true,
        size: 22,
        font: "Arial",
        color: BLUE_MID,
      }),
    ],
    spacing: { before: 160, after: 80 },
  });
}

function para(text, options = {}) {
  return new Paragraph({
    children: [
      new TextRun({ text, size: 22, font: "Times New Roman", ...options }),
    ],
    spacing: { before: 80, after: 80, line: 276 },
    alignment: AlignmentType.JUSTIFIED,
  });
}

function bullet(text) {
  return new Paragraph({
    numbering: { reference: "bullets", level: 0 },
    children: [new TextRun({ text, size: 22, font: "Times New Roman" })],
    spacing: { before: 60, after: 60 },
  });
}

function numbered(text, ref = "numbers") {
  return new Paragraph({
    numbering: { reference: ref, level: 0 },
    children: [new TextRun({ text, size: 22, font: "Times New Roman" })],
    spacing: { before: 60, after: 60 },
  });
}

function pageBreak() {
  return new Paragraph({ children: [new PageBreak()] });
}

function spacer() {
  return new Paragraph({
    children: [new TextRun("")],
    spacing: { before: 60, after: 60 },
  });
}

function tableCell(
  text,
  isHeader = false,
  colSpan = 1,
  width = 2340,
  align = AlignmentType.LEFT,
) {
  return new TableCell({
    borders,
    width: { size: width, type: WidthType.DXA },
    columnSpan: colSpan,
    shading: { fill: isHeader ? BLUE_HEADER : WHITE, type: ShadingType.CLEAR },
    margins: { top: 100, bottom: 100, left: 150, right: 150 },
    verticalAlign: VerticalAlign.CENTER,
    children: [
      new Paragraph({
        alignment: align,
        children: [
          new TextRun({
            text,
            bold: isHeader,
            size: isHeader ? 20 : 20,
            font: "Arial",
            color: isHeader ? WHITE : "000000",
          }),
        ],
      }),
    ],
  });
}

function infoBox(label, content) {
  return new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2000, 7360],
    rows: [
      new TableRow({
        children: [
          new TableCell({
            borders,
            width: { size: 2000, type: WidthType.DXA },
            shading: { fill: BLUE_LIGHT, type: ShadingType.CLEAR },
            margins: { top: 80, bottom: 80, left: 120, right: 120 },
            children: [
              new Paragraph({
                children: [
                  new TextRun({
                    text: label,
                    bold: true,
                    size: 20,
                    font: "Arial",
                    color: BLUE_DARK,
                  }),
                ],
              }),
            ],
          }),
          new TableCell({
            borders,
            width: { size: 7360, type: WidthType.DXA },
            margins: { top: 80, bottom: 80, left: 120, right: 120 },
            children: [
              new Paragraph({
                children: [
                  new TextRun({
                    text: content,
                    size: 20,
                    font: "Times New Roman",
                  }),
                ],
              }),
            ],
          }),
        ],
      }),
    ],
  });
}

// ============================================================
// COVER PAGE
// ============================================================
const coverPage = [
  spacer(),
  spacer(),
  spacer(),
  new Paragraph({
    alignment: AlignmentType.CENTER,
    children: [
      new TextRun({
        text: "BLUEPRINT PROTOTIPE",
        bold: true,
        size: 52,
        font: "Arial",
        color: BLUE_DARK,
      }),
    ],
    spacing: { before: 200, after: 160 },
  }),
  new Paragraph({
    alignment: AlignmentType.CENTER,
    children: [
      new TextRun({
        text: "Transformation Smart Tourism:",
        bold: true,
        size: 36,
        font: "Arial",
        color: BLUE_MID,
      }),
    ],
    spacing: { before: 0, after: 80 },
  }),
  new Paragraph({
    alignment: AlignmentType.CENTER,
    children: [
      new TextRun({
        text: "Hilirisasi Prototipe Sistem Cerdas Pemetaan Potensi Wisata",
        bold: true,
        size: 32,
        font: "Arial",
        color: BLUE_MID,
      }),
    ],
    spacing: { before: 0, after: 80 },
  }),
  new Paragraph({
    alignment: AlignmentType.CENTER,
    children: [
      new TextRun({
        text: "Menggunakan Algoritma K-Means Clustering",
        bold: true,
        size: 32,
        font: "Arial",
        color: BLUE_MID,
      }),
    ],
    spacing: { before: 0, after: 80 },
  }),
  new Paragraph({
    alignment: AlignmentType.CENTER,
    children: [
      new TextRun({
        text: "di Kabupaten Magelang",
        bold: true,
        size: 30,
        font: "Arial",
        color: BLUE_DARK,
      }),
    ],
    spacing: { before: 0, after: 300 },
  }),
  new Paragraph({
    alignment: AlignmentType.CENTER,
    border: {
      top: { style: BorderStyle.SINGLE, size: 6, color: BLUE_MID },
      bottom: { style: BorderStyle.SINGLE, size: 6, color: BLUE_MID },
    },
    children: [new TextRun({ text: " ", size: 4 })],
    spacing: { before: 20, after: 200 },
  }),
  spacer(),
  spacer(),
  new Table({
    width: { size: 7000, type: WidthType.DXA },
    columnWidths: [2500, 4500],
    rows: [
      [
        "Program",
        "Hilirisasi Riset Prioritas – Pengujian Model dan Prototipe Tahun 2026",
      ],
      ["Teknologi", "PHP (Laravel/Native), MySQL, K-Means Clustering, GIS Web"],
      ["TKT Target", "TKT 5 – Demonstrasi Prototipe pada Lingkungan Relevan"],
      ["Lokasi", "Kabupaten Magelang, Jawa Tengah"],
      ["Tahun", "2026"],
    ].map(
      ([k, v]) =>
        new TableRow({
          children: [
            new TableCell({
              borders,
              width: { size: 2500, type: WidthType.DXA },
              shading: { fill: BLUE_LIGHT, type: ShadingType.CLEAR },
              margins: { top: 80, bottom: 80, left: 120, right: 120 },
              children: [
                new Paragraph({
                  children: [
                    new TextRun({
                      text: k,
                      bold: true,
                      size: 20,
                      font: "Arial",
                    }),
                  ],
                }),
              ],
            }),
            new TableCell({
              borders,
              width: { size: 4500, type: WidthType.DXA },
              margins: { top: 80, bottom: 80, left: 120, right: 120 },
              children: [
                new Paragraph({
                  children: [
                    new TextRun({ text: v, size: 20, font: "Times New Roman" }),
                  ],
                }),
              ],
            }),
          ],
        }),
    ),
  }),
  pageBreak(),
];

// ============================================================
// BAB 1 - PENDAHULUAN BLUEPRINT
// ============================================================
const bab1 = [
  h1("BAB I   PENDAHULUAN BLUEPRINT"),
  spacer(),
  h2("1.1  Tujuan Dokumen"),
  para(
    "Dokumen Blueprint ini merupakan panduan teknis lengkap pengembangan Prototipe Sistem Cerdas Pemetaan Potensi Wisata berbasis K-Means Clustering untuk Kabupaten Magelang. Blueprint ini menjabarkan arsitektur sistem, rancangan basis data, alur kerja proses (flowchart), spesifikasi modul, desain antarmuka (UI/UX), teknologi yang digunakan, serta rencana pengujian dan validasi dalam rangka peningkatan Tingkat Kesiapterapan Teknologi (TKT) dari TKT 4 ke TKT 5.",
  ),
  spacer(),
  h2("1.2  Ruang Lingkup Sistem"),
  para("Sistem yang dikembangkan mencakup:"),
  bullet("Modul manajemen data potensi wisata berbasis web"),
  bullet("Modul pemrosesan algoritma K-Means Clustering"),
  bullet("Modul visualisasi peta digital interaktif (GIS Web)"),
  bullet("Modul laporan dan analisis hasil clustering"),
  bullet("Modul autentikasi dan manajemen pengguna"),
  bullet("Dashboard admin untuk Dinas Pariwisata dan Bappeda"),
  spacer(),
  h2("1.3  Identifikasi Pengguna (Stakeholder)"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [600, 2500, 2200, 4060],
    rows: [
      new TableRow({
        children: [
          tableCell("No", true, 1, 600, AlignmentType.CENTER),
          tableCell("Pengguna", true, 1, 2500),
          tableCell("Peran", true, 1, 2200),
          tableCell("Hak Akses", true, 1, 4060),
        ],
      }),
      new TableRow({
        children: [
          tableCell("1", false, 1, 600, AlignmentType.CENTER),
          tableCell("Admin Sistem", false, 1, 2500),
          tableCell("Pengelola teknis", false, 1, 2200),
          tableCell(
            "CRUD semua data, manajemen user, konfigurasi clustering",
            false,
            1,
            4060,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("2", false, 1, 600, AlignmentType.CENTER),
          tableCell("Dinas Pariwisata", false, 1, 2500),
          tableCell("Operator kebijakan", false, 1, 2200),
          tableCell(
            "Input data wisata, lihat hasil clustering, cetak laporan",
            false,
            1,
            4060,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("3", false, 1, 600, AlignmentType.CENTER),
          tableCell("Bappeda", false, 1, 2500),
          tableCell("Perencana daerah", false, 1, 2200),
          tableCell(
            "Akses dashboard analitik, ekspor laporan spasial",
            false,
            1,
            4060,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("4", false, 1, 600, AlignmentType.CENTER),
          tableCell("Pengelola Wisata", false, 1, 2500),
          tableCell("Input dan pemantau", false, 1, 2200),
          tableCell(
            "Input data destinasi, lihat posisi cluster destinasinya",
            false,
            1,
            4060,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("5", false, 1, 600, AlignmentType.CENTER),
          tableCell("Publik/Wisatawan", false, 1, 2500),
          tableCell("Pengguna akhir", false, 1, 2200),
          tableCell(
            "Lihat peta wisata, cari destinasi berdasarkan klaster",
            false,
            1,
            4060,
          ),
        ],
      }),
    ],
  }),
  pageBreak(),
];

// ============================================================
// BAB 2 - ARSITEKTUR SISTEM
// ============================================================
const bab2 = [
  h1("BAB II   ARSITEKTUR SISTEM"),
  spacer(),
  h2("2.1  Gambaran Umum Arsitektur"),
  para(
    "Sistem dibangun menggunakan arsitektur Three-Tier (3-Tier Architecture) yang memisahkan lapisan presentasi (Presentation Layer), lapisan logika bisnis (Business Logic Layer), dan lapisan data (Data Layer). Teknologi utama yang digunakan adalah PHP sebagai bahasa pemrograman server-side dan MySQL sebagai sistem manajemen basis data relasional.",
  ),
  spacer(),
  h3("2.1.1  Tiga Lapisan Arsitektur"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2500, 2500, 4360],
    rows: [
      new TableRow({
        children: [
          tableCell("Lapisan", true, 1, 2500),
          tableCell("Komponen", true, 1, 2500),
          tableCell("Deskripsi", true, 1, 4360),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Presentation Layer", false, 1, 2500),
          tableCell("Browser (HTML, CSS, JS)", false, 1, 2500),
          tableCell(
            "Antarmuka pengguna berbasis web responsif, menggunakan Leaflet.js untuk peta dan Chart.js untuk grafik analitik",
            false,
            1,
            4360,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Business Logic Layer", false, 1, 2500),
          tableCell("PHP 8.x (Native/Framework)", false, 1, 2500),
          tableCell(
            "Memproses permintaan pengguna, menjalankan algoritma K-Means Clustering, kalkulasi Elbow Method, normalisasi data, dan manajemen sesi",
            false,
            1,
            4360,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Data Layer", false, 1, 2500),
          tableCell("MySQL 8.x + phpMyAdmin", false, 1, 2500),
          tableCell(
            "Menyimpan seluruh data destinasi wisata, hasil clustering, data spasial (koordinat), serta log aktivitas sistem",
            false,
            1,
            4360,
          ),
        ],
      }),
    ],
  }),
  spacer(),
  h2("2.2  Stack Teknologi Lengkap"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2200, 2800, 4360],
    rows: [
      new TableRow({
        children: [
          tableCell("Kategori", true, 1, 2200),
          tableCell("Teknologi / Tools", true, 1, 2800),
          tableCell("Versi & Keterangan", true, 1, 4360),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Backend Language", false, 1, 2200),
          tableCell("PHP", false, 1, 2800),
          tableCell(
            "PHP 8.1+ – bahasa utama server-side, OOP, MVC pattern",
            false,
            1,
            4360,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Database", false, 1, 2200),
          tableCell("MySQL", false, 1, 2800),
          tableCell(
            "MySQL 8.0 – RDBMS untuk penyimpanan data relasional dan spasial",
            false,
            1,
            4360,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Web Server", false, 1, 2200),
          tableCell("Apache / Nginx", false, 1, 2800),
          tableCell(
            "Apache 2.4 (XAMPP/Ubuntu) – Web server lokal dan produksi",
            false,
            1,
            4360,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Frontend Framework", false, 1, 2200),
          tableCell("Bootstrap 5", false, 1, 2800),
          tableCell(
            "Bootstrap 5.3 – Framework CSS responsif untuk UI modern",
            false,
            1,
            4360,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Template Engine", false, 1, 2200),
          tableCell("Blade / PHP Native", false, 1, 2800),
          tableCell("Template rendering untuk halaman dinamis", false, 1, 4360),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Peta Interaktif", false, 1, 2200),
          tableCell("Leaflet.js", false, 1, 2800),
          tableCell(
            "Leaflet 1.9 – Library peta open-source dengan tile layer OpenStreetMap",
            false,
            1,
            4360,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Visualisasi Grafik", false, 1, 2200),
          tableCell("Chart.js", false, 1, 2800),
          tableCell(
            "Chart.js 4.x – Grafik dinamis (pie, bar, scatter plot Elbow Method)",
            false,
            1,
            4360,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Algoritma ML", false, 1, 2200),
          tableCell("K-Means (PHP custom)", false, 1, 2800),
          tableCell(
            "Implementasi native PHP: normalisasi Min-Max, inisialisasi centroid, iterasi hingga konvergen",
            false,
            1,
            4360,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("DB Manager", false, 1, 2200),
          tableCell("phpMyAdmin", false, 1, 2800),
          tableCell("Manajemen basis data berbasis web", false, 1, 4360),
        ],
      }),
      new TableRow({
        children: [
          tableCell("HTTP Request", false, 1, 2200),
          tableCell("Fetch API / jQuery Ajax", false, 1, 2800),
          tableCell(
            "Komunikasi asinkron antara frontend dan backend",
            false,
            1,
            4360,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Export Data", false, 1, 2200),
          tableCell("PhpSpreadsheet / TCPDF", false, 1, 2800),
          tableCell(
            "Ekspor laporan ke format Excel (.xlsx) dan PDF",
            false,
            1,
            4360,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Version Control", false, 1, 2200),
          tableCell("Git + GitHub", false, 1, 2800),
          tableCell(
            "Pengelolaan kode sumber dan kolaborasi tim pengembang",
            false,
            1,
            4360,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Environment Dev", false, 1, 2200),
          tableCell("XAMPP / Docker", false, 1, 2800),
          tableCell(
            "Lingkungan pengembangan lokal untuk testing sebelum deployment",
            false,
            1,
            4360,
          ),
        ],
      }),
    ],
  }),
  spacer(),
  h2("2.3  Diagram Komponen Sistem"),
  para(
    "Arsitektur komponen sistem digambarkan sebagai berikut. Pengguna mengakses sistem melalui browser web. Permintaan dikirim ke Web Server (Apache) yang meneruskan ke PHP Application Engine. PHP Engine berkomunikasi dengan MySQL Database untuk operasi CRUD data, dan memanggil modul K-Means Clustering Engine untuk pemrosesan algoritma. Hasil clustering dikembalikan ke frontend dalam format JSON, kemudian divisualisasikan menggunakan Leaflet.js (peta) dan Chart.js (grafik).",
  ),
  spacer(),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [9360],
    rows: [
      new TableRow({
        children: [
          new TableCell({
            borders,
            shading: { fill: BLUE_LIGHT, type: ShadingType.CLEAR },
            margins: { top: 120, bottom: 120, left: 200, right: 200 },
            children: [
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ BROWSER / CLIENT ]",
                    bold: true,
                    size: 22,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "HTML5 + Bootstrap 5 + Leaflet.js + Chart.js + Fetch API",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "↕ HTTP/HTTPS Request & Response",
                    size: 20,
                    font: "Courier New",
                    color: BLUE_MID,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ WEB SERVER – Apache 2.4 ]",
                    bold: true,
                    size: 22,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "Routing URL, Virtual Host, .htaccess rewrite rules",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "↕ PHP FastCGI",
                    size: 20,
                    font: "Courier New",
                    color: BLUE_MID,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ PHP 8.1 APPLICATION ENGINE ]",
                    bold: true,
                    size: 22,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "Controller | Model | View | K-Means Engine | Auth Manager | Report Generator",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "↕ PDO / MySQLi",
                    size: 20,
                    font: "Courier New",
                    color: BLUE_MID,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ DATABASE – MySQL 8.0 ]",
                    bold: true,
                    size: 22,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "tb_destinasi | tb_kriteria | tb_cluster | tb_hasil_cluster | tb_users | tb_log",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
            ],
          }),
        ],
      }),
    ],
  }),
  pageBreak(),
];

// ============================================================
// BAB 3 - DESAIN BASIS DATA (ERD)
// ============================================================
const bab3 = [
  h1("BAB III   DESAIN BASIS DATA (ERD & SKEMA TABEL)"),
  spacer(),
  h2("3.1  Entitas Utama dan Relasi"),
  para(
    "Basis data sistem terdiri dari 9 tabel utama yang saling berelasi. Relasi antar entitas menggunakan foreign key dengan referential integrity untuk menjaga konsistensi data.",
  ),
  spacer(),
  h3("3.1.1  Diagram ERD (Entity Relationship Diagram)"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [9360],
    rows: [
      new TableRow({
        children: [
          new TableCell({
            borders,
            shading: { fill: BLUE_LIGHT, type: ShadingType.CLEAR },
            margins: { top: 200, bottom: 200, left: 300, right: 300 },
            children: [
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "DIAGRAM ERD – Sistem Pemetaan Potensi Wisata K-Means",
                    bold: true,
                    size: 24,
                    font: "Arial",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              spacer(),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "┌─────────────────────┐          ┌──────────────────────┐",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│      tb_users        │          │    tb_destinasi       │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│─────────────────────│ 1      M │──────────────────────│",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│ PK id_user           │──────────│ PK id_destinasi       │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│    nama_user         │          │    nama_destinasi     │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│    email             │          │    kategori           │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│    password (hash)   │          │    kecamatan          │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│    role              │          │    latitude           │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│    created_at        │          │    longitude          │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "└─────────────────────┘          │ FK id_user (created)  │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "                                  └──────────┬───────────┘",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "                                             │ 1",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "                     ┌───────────────────────┼─────────────────────┐",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "                     │ M                     │ 1                   │ M",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       ┌─────────────┴──────┐  ┌──┴─────────────────┐  ┌┴──────────────────┐",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       │   tb_nilai_kriteria │  │   tb_hasil_cluster  │  │  tb_cluster        │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       │────────────────────│  │────────────────────│  │────────────────────│",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       │ PK id_nilai         │  │ PK id_hasil         │  │ PK id_cluster       │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       │ FK id_destinasi     │  │ FK id_destinasi     │  │    nama_cluster     │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       │ FK id_kriteria      │  │ FK id_cluster       │  │    keterangan       │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       │    nilai            │  │ FK id_run           │  │    warna_marker     │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       └────────────────────┘  └────────────────────┘  └────────────────────┘",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "                                         │ M",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       ┌──────────────────────┐  ┌──┴─────────────────────────┐",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       │     tb_kriteria        │  │    tb_clustering_run        │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       │───────────────────────│  │─────────────────────────────│",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       │ PK id_kriteria         │  │ PK id_run                   │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       │    nama_kriteria       │  │    jumlah_k                 │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       │    bobot               │  │    iterasi                  │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       │    satuan              │  │    nilai_wsse               │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "       └───────────────────────┘  │    tgl_run                  │",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "                                   └─────────────────────────────┘",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
            ],
          }),
        ],
      }),
    ],
  }),
  spacer(),
  h2("3.2  Definisi Tabel Basis Data"),
  spacer(),
  h3("3.2.1  Tabel tb_destinasi"),
  para("Menyimpan data utama setiap destinasi wisata di Kabupaten Magelang."),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2200, 1800, 1200, 4160],
    rows: [
      new TableRow({
        children: [
          tableCell("Field", true, 1, 2200),
          tableCell("Tipe Data", true, 1, 1800),
          tableCell("Constraint", true, 1, 1200),
          tableCell("Keterangan", true, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("id_destinasi", false, 1, 2200),
          tableCell("INT(11)", false, 1, 1800),
          tableCell("PK, AI", false, 1, 1200),
          tableCell("Primary key auto increment", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("nama_destinasi", false, 1, 2200),
          tableCell("VARCHAR(200)", false, 1, 1800),
          tableCell("NOT NULL", false, 1, 1200),
          tableCell("Nama destinasi wisata", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("kategori", false, 1, 2200),
          tableCell("ENUM", false, 1, 1800),
          tableCell("NOT NULL", false, 1, 1200),
          tableCell(
            "Budaya, Alam, Religi, Desa Wisata, Kuliner",
            false,
            1,
            4160,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("kecamatan", false, 1, 2200),
          tableCell("VARCHAR(100)", false, 1, 1800),
          tableCell("NOT NULL", false, 1, 1200),
          tableCell("Nama kecamatan lokasi destinasi", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("alamat", false, 1, 2200),
          tableCell("TEXT", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell("Alamat lengkap destinasi", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("latitude", false, 1, 2200),
          tableCell("DECIMAL(10,7)", false, 1, 1800),
          tableCell("NOT NULL", false, 1, 1200),
          tableCell("Koordinat lintang untuk peta", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("longitude", false, 1, 2200),
          tableCell("DECIMAL(10,7)", false, 1, 1800),
          tableCell("NOT NULL", false, 1, 1200),
          tableCell("Koordinat bujur untuk peta", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("deskripsi", false, 1, 2200),
          tableCell("TEXT", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell("Deskripsi singkat destinasi", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("foto", false, 1, 2200),
          tableCell("VARCHAR(255)", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell("Path file foto destinasi", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("id_user", false, 1, 2200),
          tableCell("INT(11)", false, 1, 1800),
          tableCell("FK", false, 1, 1200),
          tableCell("Referensi ke tb_users (petugas input)", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("status", false, 1, 2200),
          tableCell("ENUM", false, 1, 1800),
          tableCell("DEFAULT aktif", false, 1, 1200),
          tableCell("aktif / nonaktif", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("created_at", false, 1, 2200),
          tableCell("TIMESTAMP", false, 1, 1800),
          tableCell("DEFAULT NOW()", false, 1, 1200),
          tableCell("Waktu data dibuat", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("updated_at", false, 1, 2200),
          tableCell("TIMESTAMP", false, 1, 1800),
          tableCell("ON UPDATE NOW()", false, 1, 1200),
          tableCell("Waktu data terakhir diperbarui", false, 1, 4160),
        ],
      }),
    ],
  }),
  spacer(),
  h3("3.2.2  Tabel tb_kriteria"),
  para(
    "Menyimpan kriteria penilaian yang digunakan sebagai variabel input K-Means Clustering.",
  ),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2200, 1800, 1200, 4160],
    rows: [
      new TableRow({
        children: [
          tableCell("Field", true, 1, 2200),
          tableCell("Tipe Data", true, 1, 1800),
          tableCell("Constraint", true, 1, 1200),
          tableCell("Keterangan", true, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("id_kriteria", false, 1, 2200),
          tableCell("INT(11)", false, 1, 1800),
          tableCell("PK, AI", false, 1, 1200),
          tableCell("Primary key", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("nama_kriteria", false, 1, 2200),
          tableCell("VARCHAR(150)", false, 1, 1800),
          tableCell("NOT NULL", false, 1, 1200),
          tableCell(
            "Mis: Daya Tarik, Aksesibilitas, Fasilitas, Pengunjung/Tahun, Pengelolaan",
            false,
            1,
            4160,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("bobot", false, 1, 2200),
          tableCell("DECIMAL(5,2)", false, 1, 1800),
          tableCell("NOT NULL", false, 1, 1200),
          tableCell("Bobot kepentingan kriteria (0.0–1.0)", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("satuan", false, 1, 2200),
          tableCell("VARCHAR(50)", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell(
            "Satuan pengukuran (misal: orang, skor 1-5, km)",
            false,
            1,
            4160,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("skala_min", false, 1, 2200),
          tableCell("DECIMAL(10,2)", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell("Nilai minimum untuk normalisasi Min-Max", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("skala_max", false, 1, 2200),
          tableCell("DECIMAL(10,2)", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell("Nilai maksimum untuk normalisasi Min-Max", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("keterangan", false, 1, 2200),
          tableCell("TEXT", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell("Penjelasan cara pengisian nilai kriteria", false, 1, 4160),
        ],
      }),
    ],
  }),
  spacer(),
  h3("3.2.3  Tabel tb_nilai_kriteria"),
  para(
    "Menyimpan nilai setiap kriteria untuk setiap destinasi wisata (many-to-many antara destinasi dan kriteria).",
  ),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2200, 1800, 1200, 4160],
    rows: [
      new TableRow({
        children: [
          tableCell("Field", true, 1, 2200),
          tableCell("Tipe Data", true, 1, 1800),
          tableCell("Constraint", true, 1, 1200),
          tableCell("Keterangan", true, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("id_nilai", false, 1, 2200),
          tableCell("INT(11)", false, 1, 1800),
          tableCell("PK, AI", false, 1, 1200),
          tableCell("Primary key", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("id_destinasi", false, 1, 2200),
          tableCell("INT(11)", false, 1, 1800),
          tableCell("FK NOT NULL", false, 1, 1200),
          tableCell("Referensi ke tb_destinasi", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("id_kriteria", false, 1, 2200),
          tableCell("INT(11)", false, 1, 1800),
          tableCell("FK NOT NULL", false, 1, 1200),
          tableCell("Referensi ke tb_kriteria", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("nilai_asli", false, 1, 2200),
          tableCell("DECIMAL(12,4)", false, 1, 1800),
          tableCell("NOT NULL", false, 1, 1200),
          tableCell("Nilai asli sebelum normalisasi", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("nilai_normal", false, 1, 2200),
          tableCell("DECIMAL(10,8)", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell(
            "Nilai hasil normalisasi Min-Max Scaler (0–1)",
            false,
            1,
            4160,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("updated_at", false, 1, 2200),
          tableCell("TIMESTAMP", false, 1, 1800),
          tableCell("ON UPDATE NOW()", false, 1, 1200),
          tableCell("Waktu terakhir nilai diperbarui", false, 1, 4160),
        ],
      }),
    ],
  }),
  spacer(),
  h3("3.2.4  Tabel tb_clustering_run"),
  para(
    "Menyimpan log setiap sesi proses clustering yang dijalankan beserta parameternya.",
  ),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2200, 1800, 1200, 4160],
    rows: [
      new TableRow({
        children: [
          tableCell("Field", true, 1, 2200),
          tableCell("Tipe Data", true, 1, 1800),
          tableCell("Constraint", true, 1, 1200),
          tableCell("Keterangan", true, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("id_run", false, 1, 2200),
          tableCell("INT(11)", false, 1, 1800),
          tableCell("PK, AI", false, 1, 1200),
          tableCell("Primary key sesi clustering", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("jumlah_k", false, 1, 2200),
          tableCell("TINYINT", false, 1, 1800),
          tableCell("NOT NULL", false, 1, 1200),
          tableCell("Jumlah klaster K yang digunakan", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("metode_init", false, 1, 2200),
          tableCell("VARCHAR(50)", false, 1, 1800),
          tableCell("DEFAULT random", false, 1, 1200),
          tableCell(
            "Metode inisialisasi centroid: random / K-Means++",
            false,
            1,
            4160,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("max_iterasi", false, 1, 2200),
          tableCell("INT", false, 1, 1800),
          tableCell("DEFAULT 100", false, 1, 1200),
          tableCell("Batas maksimum iterasi", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("iterasi_aktual", false, 1, 2200),
          tableCell("INT", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell("Jumlah iterasi aktual hingga konvergen", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("nilai_wsse", false, 1, 2200),
          tableCell("DECIMAL(15,6)", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell(
            "Within-Cluster Sum of Squared Errors (Elbow)",
            false,
            1,
            4160,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("nilai_silhouette", false, 1, 2200),
          tableCell("DECIMAL(8,6)", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell("Silhouette Coefficient (-1 hingga 1)", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("tgl_run", false, 1, 2200),
          tableCell("TIMESTAMP", false, 1, 1800),
          tableCell("DEFAULT NOW()", false, 1, 1200),
          tableCell("Tanggal dan waktu clustering dijalankan", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("id_user", false, 1, 2200),
          tableCell("INT(11)", false, 1, 1800),
          tableCell("FK", false, 1, 1200),
          tableCell("User yang menjalankan proses clustering", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("status", false, 1, 2200),
          tableCell("ENUM", false, 1, 1800),
          tableCell("DEFAULT selesai", false, 1, 1200),
          tableCell("proses / selesai / gagal", false, 1, 4160),
        ],
      }),
    ],
  }),
  spacer(),
  h3("3.2.5  Tabel tb_cluster"),
  para(
    "Mendefinisikan setiap klaster yang dihasilkan dari proses K-Means Clustering beserta interpretasinya.",
  ),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2200, 1800, 1200, 4160],
    rows: [
      new TableRow({
        children: [
          tableCell("Field", true, 1, 2200),
          tableCell("Tipe Data", true, 1, 1800),
          tableCell("Constraint", true, 1, 1200),
          tableCell("Keterangan", true, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("id_cluster", false, 1, 2200),
          tableCell("INT(11)", false, 1, 1800),
          tableCell("PK, AI", false, 1, 1200),
          tableCell("Primary key klaster", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("id_run", false, 1, 2200),
          tableCell("INT(11)", false, 1, 1800),
          tableCell("FK NOT NULL", false, 1, 1200),
          tableCell(
            "Referensi ke sesi clustering (tb_clustering_run)",
            false,
            1,
            4160,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("nomor_cluster", false, 1, 2200),
          tableCell("TINYINT", false, 1, 1800),
          tableCell("NOT NULL", false, 1, 1200),
          tableCell("Nomor urut klaster (1, 2, 3, ...)", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("nama_cluster", false, 1, 2200),
          tableCell("VARCHAR(100)", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell(
            "Label klaster (mis: Potensi Tinggi, Menengah, Rendah)",
            false,
            1,
            4160,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("warna_marker", false, 1, 2200),
          tableCell("VARCHAR(10)", false, 1, 1800),
          tableCell("DEFAULT #FF0000", false, 1, 1200),
          tableCell("Warna hex untuk marker peta Leaflet.js", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("nilai_centroid", false, 1, 2200),
          tableCell("JSON", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell(
            "Nilai centroid final dalam format JSON array",
            false,
            1,
            4160,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("keterangan", false, 1, 2200),
          tableCell("TEXT", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell("Deskripsi karakteristik klaster", false, 1, 4160),
        ],
      }),
    ],
  }),
  spacer(),
  h3("3.2.6  Tabel tb_hasil_cluster"),
  para(
    "Menyimpan hasil assignment setiap destinasi ke dalam klaster tertentu.",
  ),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2200, 1800, 1200, 4160],
    rows: [
      new TableRow({
        children: [
          tableCell("Field", true, 1, 2200),
          tableCell("Tipe Data", true, 1, 1800),
          tableCell("Constraint", true, 1, 1200),
          tableCell("Keterangan", true, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("id_hasil", false, 1, 2200),
          tableCell("INT(11)", false, 1, 1800),
          tableCell("PK, AI", false, 1, 1200),
          tableCell("Primary key hasil", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("id_run", false, 1, 2200),
          tableCell("INT(11)", false, 1, 1800),
          tableCell("FK NOT NULL", false, 1, 1200),
          tableCell("Referensi ke sesi clustering", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("id_destinasi", false, 1, 2200),
          tableCell("INT(11)", false, 1, 1800),
          tableCell("FK NOT NULL", false, 1, 1200),
          tableCell("Referensi ke destinasi wisata", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("id_cluster", false, 1, 2200),
          tableCell("INT(11)", false, 1, 1800),
          tableCell("FK NOT NULL", false, 1, 1200),
          tableCell("Klaster yang ditempati destinasi ini", false, 1, 4160),
        ],
      }),
      new TableRow({
        children: [
          tableCell("jarak_ke_centroid", false, 1, 2200),
          tableCell("DECIMAL(15,8)", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell(
            "Jarak Euclidean dari destinasi ke centroid klasternya",
            false,
            1,
            4160,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("iterasi_masuk", false, 1, 2200),
          tableCell("INT", false, 1, 1800),
          tableCell("NULL", false, 1, 1200),
          tableCell(
            "Iterasi ke berapa destinasi ini ditetapkan ke klaster ini",
            false,
            1,
            4160,
          ),
        ],
      }),
    ],
  }),
  pageBreak(),
];

// ============================================================
// BAB 4 - FLOWCHART
// ============================================================
const bab4 = [
  h1("BAB IV   FLOWCHART ALUR KERJA SISTEM"),
  spacer(),
  h2("4.1  Flowchart Sistem Utama (Main System Flow)"),
  para(
    "Flowchart berikut menggambarkan alur kerja utama sistem dari login hingga visualisasi hasil clustering.",
  ),
  spacer(),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [9360],
    rows: [
      new TableRow({
        children: [
          new TableCell({
            borders,
            shading: { fill: BLUE_LIGHT, type: ShadingType.CLEAR },
            margins: { top: 200, bottom: 200, left: 400, right: 400 },
            children: [
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "FLOWCHART ALUR SISTEM UTAMA",
                    bold: true,
                    size: 22,
                    font: "Arial",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              spacer(),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "( MULAI )",
                    bold: true,
                    size: 20,
                    font: "Courier New",
                    color: BLUE_MID,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ▼",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ Halaman Login ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │ Input Username & Password",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ▼",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "< Autentikasi Valid? >──── Tidak ───► [ Tampil Pesan Error ] ──► Kembali Login",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │ Ya",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ▼",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ Dashboard Utama ] ─── Cek Role Pengguna",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ├──────────────────────────────────────────────┐",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │                                                │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[Manajemen Data Wisata]                   [Proses Clustering]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: " Input/Edit/Hapus Destinasi               Tentukan Nilai K",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: " Input Nilai Kriteria                     Jalankan K-Means",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │                                                │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     └──────────────────────┬─────────────────────────┘",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "                            │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "                            ▼",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "               [ Visualisasi Peta Klaster ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "            Leaflet.js + Marker Warna per Klaster",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "                            │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "                            ▼",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "                   [ Cetak / Ekspor Laporan ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "                      PDF / Excel / Screenshot",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "                            │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "                      ( SELESAI )",
                    bold: true,
                    size: 20,
                    font: "Courier New",
                    color: BLUE_MID,
                  }),
                ],
              }),
            ],
          }),
        ],
      }),
    ],
  }),
  spacer(),
  h2("4.2  Flowchart Algoritma K-Means Clustering"),
  para(
    "Berikut adalah flowchart detail proses algoritma K-Means Clustering yang diimplementasikan dalam bahasa PHP.",
  ),
  spacer(),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [9360],
    rows: [
      new TableRow({
        children: [
          new TableCell({
            borders,
            shading: { fill: BLUE_LIGHT, type: ShadingType.CLEAR },
            margins: { top: 200, bottom: 200, left: 400, right: 400 },
            children: [
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "FLOWCHART ALGORITMA K-MEANS CLUSTERING (PHP)",
                    bold: true,
                    size: 22,
                    font: "Arial",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              spacer(),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "( MULAI )",
                    bold: true,
                    size: 20,
                    font: "Courier New",
                    color: BLUE_MID,
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ▼",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ Ambil Data Destinasi + Nilai Kriteria dari MySQL ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "  SELECT d.*, nk.nilai_asli FROM tb_destinasi d",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "  JOIN tb_nilai_kriteria nk ON d.id_destinasi = nk.id_destinasi",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ▼",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ Normalisasi Data – Min-Max Scaler ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "  x' = (x - x_min) / (x_max - x_min)",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ▼",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ Inisialisasi K Centroid Secara Acak ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "  Pilih K data secara random sebagai centroid awal",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ▼  ◄──────────────────────────────────────────────┐",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ Hitung Jarak Euclidean setiap Data ke semua Centroid ]              │",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "  d = sqrt(Σ(xᵢ - cᵢ)²)  untuk setiap dimensi kriteria          │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │                                                               │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ▼                                                               │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ Tetapkan Setiap Destinasi ke Klaster Terdekat ]                    │",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "  cluster[i] = argmin(jarak ke centroid k)                          │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │                                                               │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ▼                                                               │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ Perbarui Centroid = Rata-rata nilai anggota klaster ]              │",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "  c_k = mean(semua x dalam klaster k)                               │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │                                                               │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ▼                                                               │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "< Centroid Berubah? & Iterasi < Max? >──── Ya ─────────────────────┘",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │ Tidak / Konvergen",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ▼",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ Hitung Evaluasi: WSSE + Silhouette Coefficient ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ▼",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ Simpan Hasil ke tb_hasil_cluster, tb_cluster, tb_clustering_run ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ▼",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "[ Return JSON: Cluster Assignment + Centroid + Evaluasi ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     │",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "     ▼",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "( SELESAI – Render Peta + Grafik )",
                    bold: true,
                    size: 20,
                    font: "Courier New",
                    color: BLUE_MID,
                  }),
                ],
              }),
            ],
          }),
        ],
      }),
    ],
  }),
  spacer(),
  h2("4.3  Flowchart Input Data Destinasi"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [9360],
    rows: [
      new TableRow({
        children: [
          new TableCell({
            borders,
            shading: { fill: GRAY_LIGHT, type: ShadingType.CLEAR },
            margins: { top: 160, bottom: 160, left: 300, right: 300 },
            children: [
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "FLOWCHART INPUT DATA DESTINASI WISATA",
                    bold: true,
                    size: 22,
                    font: "Arial",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              spacer(),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "MULAI  →  Buka Form Input Destinasi",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  │", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  ▼", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "[ Isi Form: Nama, Kategori, Kecamatan, Koordinat, Foto ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  │", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  ▼", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "< Validasi Form (PHP Server-side) > ─── Gagal ───► Tampil Error per Field",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "  │ Valid",
                    size: 20,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  ▼", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "[ Sanitasi Input: htmlspecialchars, prepared statement PDO ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  │", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  ▼", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "[ Upload Foto → Validasi MIME & Ukuran → Simpan ke /uploads/ ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  │", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  ▼", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "[ INSERT INTO tb_destinasi (PDO Prepared Statement) ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  │", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  ▼", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "[ Input Nilai Kriteria untuk Destinasi Baru ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  │", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  ▼", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "[ INSERT INTO tb_nilai_kriteria (batch insert) ]",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  │", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  ▼", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "[ Flash Message: 'Data Berhasil Disimpan' ] → Redirect Daftar",
                    size: 20,
                    font: "Courier New",
                    color: "333333",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({ text: "  │", size: 20, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "  SELESAI",
                    bold: true,
                    size: 20,
                    font: "Courier New",
                    color: BLUE_MID,
                  }),
                ],
              }),
            ],
          }),
        ],
      }),
    ],
  }),
  pageBreak(),
];

// ============================================================
// BAB 5 - SPESIFIKASI MODUL
// ============================================================
const bab5 = [
  h1("BAB V   SPESIFIKASI MODUL SISTEM"),
  spacer(),
  h2("5.1  Daftar Modul dan Fungsi"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [600, 2500, 2000, 4260],
    rows: [
      new TableRow({
        children: [
          tableCell("No", true, 1, 600, AlignmentType.CENTER),
          tableCell("Modul", true, 1, 2500),
          tableCell("File Utama (PHP)", true, 1, 2000),
          tableCell("Fungsi Utama", true, 1, 4260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("1", false, 1, 600, AlignmentType.CENTER),
          tableCell("Modul Autentikasi", false, 1, 2500),
          tableCell("auth.php, login.php", false, 1, 2000),
          tableCell(
            "Login, logout, session management, password hashing (password_hash/bcrypt), middleware role check",
            false,
            1,
            4260,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("2", false, 1, 600, AlignmentType.CENTER),
          tableCell("Modul Manajemen Destinasi", false, 1, 2500),
          tableCell("destinasi.php", false, 1, 2000),
          tableCell(
            "CRUD destinasi wisata, upload foto, tampil daftar, detail destinasi, filter per kategori/kecamatan",
            false,
            1,
            4260,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("3", false, 1, 600, AlignmentType.CENTER),
          tableCell("Modul Manajemen Kriteria", false, 1, 2500),
          tableCell("kriteria.php", false, 1, 2000),
          tableCell(
            "CRUD kriteria penilaian, atur bobot dan skala, input nilai kriteria per destinasi",
            false,
            1,
            4260,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("4", false, 1, 600, AlignmentType.CENTER),
          tableCell("Modul K-Means Engine", false, 1, 2500),
          tableCell("KMeans.php (Class)", false, 1, 2000),
          tableCell(
            "Normalisasi Min-Max, inisialisasi centroid, iterasi clustering, hitung jarak Euclidean, update centroid, evaluasi WSSE dan Silhouette",
            false,
            1,
            4260,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("5", false, 1, 600, AlignmentType.CENTER),
          tableCell("Modul Clustering Control", false, 1, 2500),
          tableCell("clustering.php", false, 1, 2000),
          tableCell(
            "Antarmuka pengaturan parameter K, trigger proses clustering, tampil progress dan hasil, simpan ke database",
            false,
            1,
            4260,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("6", false, 1, 600, AlignmentType.CENTER),
          tableCell("Modul Visualisasi Peta", false, 1, 2500),
          tableCell("peta.php, map.js", false, 1, 2000),
          tableCell(
            "Render peta Leaflet.js, tampilkan marker per klaster dengan warna berbeda, popup info destinasi, filter tampilan per klaster",
            false,
            1,
            4260,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("7", false, 1, 600, AlignmentType.CENTER),
          tableCell("Modul Analitik & Grafik", false, 1, 2500),
          tableCell("analitik.php, chart.js", false, 1, 2000),
          tableCell(
            "Grafik pie distribusi klaster, scatter plot Elbow Method, bar chart perbandingan nilai antar klaster",
            false,
            1,
            4260,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("8", false, 1, 600, AlignmentType.CENTER),
          tableCell("Modul Laporan", false, 1, 2500),
          tableCell("laporan.php", false, 1, 2000),
          tableCell(
            "Generate laporan PDF (TCPDF), ekspor Excel (PhpSpreadsheet), tampil tabel hasil clustering, filter per run",
            false,
            1,
            4260,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("9", false, 1, 600, AlignmentType.CENTER),
          tableCell("Modul Manajemen User", false, 1, 2500),
          tableCell("user.php", false, 1, 2000),
          tableCell(
            "CRUD akun pengguna, atur role (admin/operator/viewer), reset password, log aktivitas login",
            false,
            1,
            4260,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("10", false, 1, 600, AlignmentType.CENTER),
          tableCell("Modul Dashboard", false, 1, 2500),
          tableCell("dashboard.php", false, 1, 2000),
          tableCell(
            "Statistik ringkasan: total destinasi, jumlah klaster aktif, destinasi per kategori, klaster terbaru, shortcut navigasi",
            false,
            1,
            4260,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("11", false, 1, 600, AlignmentType.CENTER),
          tableCell("Modul API Endpoint", false, 1, 2500),
          tableCell("api/", false, 1, 2000),
          tableCell(
            "REST API JSON untuk Fetch/Ajax: get_destinasi, run_kmeans, get_hasil, get_peta_data",
            false,
            1,
            4260,
          ),
        ],
      }),
    ],
  }),
  spacer(),
  h2("5.2  Pseudocode K-Means Clustering (PHP)"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [9360],
    rows: [
      new TableRow({
        children: [
          new TableCell({
            borders,
            shading: { fill: "F8F8F8", type: ShadingType.CLEAR },
            margins: { top: 160, bottom: 160, left: 300, right: 300 },
            children: [
              new Paragraph({
                children: [
                  new TextRun({
                    text: "// KMeans.php – Class Implementasi K-Means Clustering",
                    size: 18,
                    font: "Courier New",
                    color: "666666",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "class KMeans {",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "    private $data;      // Array data destinasi ternormalisasi",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "    private $k;         // Jumlah klaster",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "    private $centroids; // Array centroid current",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "    private $clusters;  // Assignment klaster per data",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({ text: "", size: 18, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "    function normalize($data) {",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "        // Min-Max Scaler: x' = (x - min) / (max - min)",
                    size: 18,
                    font: "Courier New",
                    color: "666666",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "        foreach ($criteria as $col) {",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "            $min = min(array_column($data, $col));",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "            $max = max(array_column($data, $col));",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "            foreach ($data as &$row) {",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "                $row[$col.'_norm'] = ($max==$min) ? 0 : ($row[$col]-$min)/($max-$min);",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "            }",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "        }",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "        return $data;",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "    }",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({ text: "", size: 18, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "    function euclidean($point, $centroid) {",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "        // Jarak Euclidean: sqrt(sum((xi - ci)^2))",
                    size: 18,
                    font: "Courier New",
                    color: "666666",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "        $sum = 0;",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "        foreach ($point as $i => $val) {",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "            $sum += pow($val - $centroid[$i], 2);",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "        }",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "        return sqrt($sum);",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "    }",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({ text: "", size: 18, font: "Courier New" }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "    function fit($maxIter = 100) {",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "        $this->centroids = $this->randomInit(); // Inisialisasi random",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "        for ($iter = 0; $iter < $maxIter; $iter++) {",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "            $prev = $this->clusters;",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "            // Assignment step: assign ke centroid terdekat",
                    size: 18,
                    font: "Courier New",
                    color: "666666",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "            foreach ($this->data as $i => $point) {",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "                $minDist = PHP_FLOAT_MAX; $bestK = 0;",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "                foreach ($this->centroids as $k => $c) {",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "                    $d = $this->euclidean($point, $c);",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "                    if ($d < $minDist) { $minDist=$d; $bestK=$k; }",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "                }",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "                $this->clusters[$i] = $bestK;",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "            }",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "            // Update step: hitung centroid baru = rata-rata",
                    size: 18,
                    font: "Courier New",
                    color: "666666",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "            $this->updateCentroids();",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "            // Konvergensi: tidak ada perubahan assignment",
                    size: 18,
                    font: "Courier New",
                    color: "666666",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "            if ($this->clusters === $prev) break;",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "        }",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "        return ['clusters'=>$this->clusters, 'centroids'=>$this->centroids,",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "                'wsse'=>$this->calcWSSE(), 'silhouette'=>$this->calcSilhouette()];",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "    }",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "}",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
            ],
          }),
        ],
      }),
    ],
  }),
  pageBreak(),
];

// ============================================================
// BAB 6 - DESAIN UI/UX
// ============================================================
const bab6 = [
  h1("BAB VI   DESAIN ANTARMUKA PENGGUNA (UI/UX)"),
  spacer(),
  h2("6.1  Prinsip Desain"),
  para(
    "Antarmuka pengguna sistem dirancang dengan prinsip: (1) Kesederhanaan – tampilan bersih dan intuitif tanpa elemen berlebihan; (2) Responsivitas – mendukung perangkat desktop, tablet, dan mobile menggunakan Bootstrap 5; (3) Konsistensi – warna, ikon, dan tipografi konsisten di seluruh halaman; (4) Efisiensi – pengguna dapat mencapai fungsi utama maksimal dalam 3 klik; (5) Aksesibilitas – kontras warna memadai, label form jelas, dan navigasi keyboard-friendly.",
  ),
  spacer(),
  h2("6.2  Palet Warna dan Tipografi"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2500, 2000, 4860],
    rows: [
      new TableRow({
        children: [
          tableCell("Elemen", true, 1, 2500),
          tableCell("Nilai Warna", true, 1, 2000),
          tableCell("Penggunaan", true, 1, 4860),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Primary Color", false, 1, 2500),
          tableCell("#1F4E79", false, 1, 2000),
          tableCell(
            "Header, sidebar, tombol utama, judul halaman",
            false,
            1,
            4860,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Secondary Color", false, 1, 2500),
          tableCell("#2E75B6", false, 1, 2000),
          tableCell("Sub-header, border aksen, link aktif", false, 1, 4860),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Accent / Success", false, 1, 2500),
          tableCell("#28A745", false, 1, 2000),
          tableCell(
            "Badge klaster tinggi, tombol simpan, notifikasi sukses",
            false,
            1,
            4860,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Warning", false, 1, 2500),
          tableCell("#FFC107", false, 1, 2000),
          tableCell("Badge klaster menengah, peringatan", false, 1, 4860),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Danger", false, 1, 2500),
          tableCell("#DC3545", false, 1, 2000),
          tableCell(
            "Badge klaster rendah, tombol hapus, error",
            false,
            1,
            4860,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Background", false, 1, 2500),
          tableCell("#F4F6F9", false, 1, 2000),
          tableCell("Background halaman konten", false, 1, 4860),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Font Utama", false, 1, 2500),
          tableCell("Poppins / Inter", false, 1, 2000),
          tableCell(
            "Judul, label, navigasi (Google Fonts CDN)",
            false,
            1,
            4860,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Font Konten", false, 1, 2500),
          tableCell("Open Sans / Roboto", false, 1, 2000),
          tableCell("Paragraf, tabel, form input", false, 1, 4860),
        ],
      }),
    ],
  }),
  spacer(),
  h2("6.3  Wireframe Halaman-Halaman Utama"),
  spacer(),
  h3("6.3.1  Halaman Login"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [9360],
    rows: [
      new TableRow({
        children: [
          new TableCell({
            borders,
            shading: { fill: "F0F4F8", type: ShadingType.CLEAR },
            margins: { top: 200, bottom: 200, left: 400, right: 400 },
            children: [
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "┌────────────────────────────────────────────────────────────────┐",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "│          [LOGO UNIVERSITAS]  [LOGO DINAS PARIWISATA]           │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "│                                                                │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "│   ┌────────────────────────────────────────────────────────┐  │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "│   │            SISTEM PEMETAAN POTENSI WISATA              │  │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "│   │            Kabupaten Magelang – Smart Tourism           │  │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "│   │                                                        │  │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "│   │  Username : [_________________________________]        │  │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "│   │  Password : [_________________________________] [👁]   │  │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "│   │                                                        │  │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "│   │           [   🔐  MASUK KE SISTEM   ]                  │  │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "│   │                                                        │  │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "│   │          © 2026 Dinas Pariwisata Kab. Magelang          │  │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "│   └────────────────────────────────────────────────────────┘  │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.CENTER,
                children: [
                  new TextRun({
                    text: "└────────────────────────────────────────────────────────────────┘",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
            ],
          }),
        ],
      }),
    ],
  }),
  spacer(),
  h3("6.3.2  Dashboard Utama"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [9360],
    rows: [
      new TableRow({
        children: [
          new TableCell({
            borders,
            shading: { fill: "F0F4F8", type: ShadingType.CLEAR },
            margins: { top: 160, bottom: 160, left: 300, right: 300 },
            children: [
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "┌──────────────┬─────────────────────────────────────────────────────────┐",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│  NAVBAR ATAS │  🏠 Dashboard | 📍 Destinasi | ⚙️ Clustering | 🗺️ Peta  │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "├──────────────┼─────────────────────────────────────────────────────────┤",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│  SIDEBAR     │  ┌─────────────┐ ┌─────────────┐ ┌──────────────┐   │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│  🏠 Dashboard│  │ 📌 TOTAL     │ │ 🔵 KLASTER   │ │ 📊 DATA BARU │   │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│  📍 Destinasi│  │ DESTINASI   │ │ AKTIF       │ │ BULAN INI   │   │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│  📋 Kriteria │  │    [  87  ] │ │    [  3  ]  │ │    [ 12  ]  │   │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│  ⚙️ K-Means  │  └─────────────┘ └─────────────┘ └──────────────┘   │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│  🗺️ Peta     │                                                        │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│  📊 Laporan  │  ┌───────────────────────────────────────────────┐     │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│  👥 Users    │  │  PETA MINI – Sebaran Destinasi per Klaster    │     │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│  🚪 Logout   │  │  [Leaflet.js Map Preview – 400px height]      │     │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│              │  │  🔵 Klaster 1  🟡 Klaster 2  🔴 Klaster 3    │     │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│              │  └───────────────────────────────────────────────┘     │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "│              │  Tabel Destinasi Terbaru  |  Grafik Distribusi Klaster │",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "└──────────────┴─────────────────────────────────────────────────────────┘",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
            ],
          }),
        ],
      }),
    ],
  }),
  spacer(),
  h3("6.3.3  Halaman Manajemen Destinasi Wisata"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [9360],
    rows: [
      new TableRow({
        children: [
          new TableCell({
            borders,
            shading: { fill: "F0F4F8", type: ShadingType.CLEAR },
            margins: { top: 160, bottom: 160, left: 300, right: 300 },
            children: [
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "[ SIDEBAR ] │  Daftar Destinasi Wisata                                    ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  ──────────────────────────────────────────────────────────",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  [+ Tambah Destinasi]   🔍[Cari...]  Filter: [Kategori▼][Kec▼]",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  ──────────────────────────────────────────────────────────",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  No │ Nama Destinasi    │ Kategori │ Kecamatan │ Klaster │ Aksi",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  ─────────────────────────────────────────────────────────",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │   1 │ Candi Borobudur   │ Budaya   │ Borobudur │ 🔵 Tinggi │ ✏️🗑️",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │   2 │ Ketep Pass        │ Alam     │ Sawangan  │ 🟡 Sedang │ ✏️🗑️",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │   3 │ Desa Wisata Salam  │ Desa     │ Salam     │ 🔴 Rendah │ ✏️🗑️",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  ─────────────────────────────────────────────────────────",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  [◄ Prev]  Halaman 1 dari 9  [Next ►]  Tampil 10▼ per halaman",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
            ],
          }),
        ],
      }),
    ],
  }),
  spacer(),
  h3("6.3.4  Halaman Proses K-Means Clustering"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [9360],
    rows: [
      new TableRow({
        children: [
          new TableCell({
            borders,
            shading: { fill: "F0F4F8", type: ShadingType.CLEAR },
            margins: { top: 160, bottom: 160, left: 300, right: 300 },
            children: [
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "[ SIDEBAR ] │  Proses K-Means Clustering                                  ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  ─────────────────────────────────────────────────────",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  ┌─── Parameter Clustering ───────────────────────────┐   ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │  Jumlah Klaster (K) : [  3  ] (min 2, max 10)     │   ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │  Metode Inisialisasi : [Random ▼]                  │   ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │  Maksimum Iterasi    : [ 100 ]                     │   ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │  Kriteria digunakan  : [✓] Daya Tarik [✓] Akses   │   ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │                        [✓] Fasilitas [✓] Pengunjung│   ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │            [ 🚀 Jalankan Clustering ]              │   ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  └────────────────────────────────────────────────────┘   ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  ┌─── Grafik Elbow Method ───┐  ┌── Evaluasi Hasil ──────┐  ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │  [Chart.js Line Chart]   │  │ WSSE        : 0.4231   │  ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │  K=2: ██████████████████ │  │ Silhouette  : 0.7234   │  ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │  K=3: ████████           │  │ Iterasi     : 12       │  ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │  K=4: ██████             │  │ Status      : ✅ Konvergen│ ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  └──────────────────────────┘  └───────────────────────────┘  ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
            ],
          }),
        ],
      }),
    ],
  }),
  spacer(),
  h3("6.3.5  Halaman Peta Interaktif Hasil Clustering"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [9360],
    rows: [
      new TableRow({
        children: [
          new TableCell({
            borders,
            shading: { fill: "F0F4F8", type: ShadingType.CLEAR },
            margins: { top: 160, bottom: 160, left: 300, right: 300 },
            children: [
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "[ SIDEBAR ] │  Peta Sebaran Klaster Destinasi Wisata                      ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  ──────────────────────────────────────────────────────",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  Filter: [Semua Klaster▼]  [Semua Kategori▼]  [Reset]   ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  ┌────────────────────────────────────────────────────────┐ ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │                  LEAFLET.JS MAP                       │ ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │         OpenStreetMap Tile Layer                       │ ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │   🔵 •Borobudur   🟡 •Ketep Pass   🔴 •Desa Salam     │ ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │   [Popup on Click: Nama, Klaster, Nilai Kriteria]      │ ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  │   [Zoom in/out] [Layer control] [Fullscreen]           │ ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  └────────────────────────────────────────────────────────┘ ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  Legenda: 🔵 Potensi Tinggi  🟡 Potensi Sedang  🔴 Rendah  ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  [📥 Unduh Peta PNG]  [📋 Lihat Tabel]  [📄 Ekspor PDF]   ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
            ],
          }),
        ],
      }),
    ],
  }),
  spacer(),
  h3("6.3.6  Halaman Laporan Hasil Clustering"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [9360],
    rows: [
      new TableRow({
        children: [
          new TableCell({
            borders,
            shading: { fill: "F0F4F8", type: ShadingType.CLEAR },
            margins: { top: 160, bottom: 160, left: 300, right: 300 },
            children: [
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "[ SIDEBAR ] │  Laporan Hasil Clustering                                   ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  Pilih Sesi: [Run 5 – 15 Jan 2026 K=3 ▼]                  ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  ─────────────────────────────────────────────────────────",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  Ringkasan:  K=3 | Iterasi=12 | WSSE=0.42 | Silhouette=0.72",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  ─────────────────────────────────────────────────────────",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  Klaster 1 (Potensi Tinggi) – 15 destinasi             🔵   ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  Klaster 2 (Potensi Sedang) – 42 destinasi             🟡   ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  Klaster 3 (Potensi Rendah) – 30 destinasi             🔴   ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  ─────────────────────────────────────────────────────────",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                alignment: AlignmentType.LEFT,
                children: [
                  new TextRun({
                    text: "            │  [📄 Ekspor PDF]   [📊 Ekspor Excel]   [🖨️ Cetak Langsung]  ",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
            ],
          }),
        ],
      }),
    ],
  }),
  pageBreak(),
];

// ============================================================
// BAB 7 - STRUKTUR FOLDER DAN FILE
// ============================================================
const bab7 = [
  h1("BAB VII   STRUKTUR DIREKTORI PROYEK"),
  spacer(),
  h2("7.1  Struktur Folder Aplikasi PHP"),
  para(
    "Struktur direktori proyek mengikuti pola MVC (Model-View-Controller) yang diimplementasikan secara native PHP maupun menggunakan framework PHP.",
  ),
  spacer(),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [9360],
    rows: [
      new TableRow({
        children: [
          new TableCell({
            borders,
            shading: { fill: "F8F8F8", type: ShadingType.CLEAR },
            margins: { top: 160, bottom: 160, left: 300, right: 300 },
            children: [
              new Paragraph({
                children: [
                  new TextRun({
                    text: "smart-tourism-magelang/",
                    bold: true,
                    size: 20,
                    font: "Courier New",
                    color: BLUE_DARK,
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "├── app/",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_MID,
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── Controllers/",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   ├── AuthController.php        # Login, logout, session",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   ├── DestinasiController.php   # CRUD destinasi wisata",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   ├── KriteriaController.php    # CRUD kriteria penilaian",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   ├── ClusteringController.php  # Trigger & tampil hasil",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   ├── PetaController.php        # Render peta interaktif",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   ├── LaporanController.php     # Generate PDF & Excel",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   └── UserController.php        # Manajemen akun user",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── Models/",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   ├── Destinasi.php            # Query tb_destinasi",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   ├── Kriteria.php             # Query tb_kriteria",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   ├── NilaiKriteria.php        # Query tb_nilai_kriteria",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   ├── ClusteringRun.php        # Query tb_clustering_run",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   ├── Cluster.php              # Query tb_cluster",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   └── User.php                 # Query tb_users",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   └── Algorithms/",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│       ├── KMeans.php               # Core K-Means engine",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│       ├── Normalizer.php           # Min-Max scaler",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│       └── Evaluator.php            # WSSE, Silhouette calc",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "├── views/                           # Template HTML",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_MID,
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── layouts/",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   ├── main.php                # Template utama (sidebar+navbar)",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   │   └── auth.php                # Template halaman login",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── auth/login.php",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── dashboard/index.php",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── destinasi/ [index, create, edit, show].php",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── kriteria/ [index, create, edit].php",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── clustering/ [index, proses, hasil].php",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── peta/index.php",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   └── laporan/ [index, pdf, excel].php",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "├── api/                             # REST API endpoint JSON",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_MID,
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── get_destinasi.php",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── run_clustering.php",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   └── get_peta_data.php",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "├── public/                          # Aset publik (web root)",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_MID,
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── css/  [bootstrap.min.css, style.css]",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── js/   [bootstrap.bundle.js, leaflet.js, chart.js, app.js]",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── img/  [logo, icon, background]",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   └── uploads/  [foto destinasi – runtime]",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "├── config/",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_MID,
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── database.php                # Koneksi PDO MySQL",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   └── config.php                  # Konstanta global",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "├── database/",
                    size: 18,
                    font: "Courier New",
                    color: BLUE_MID,
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── schema.sql                  # DDL create table",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   └── seed.sql                    # Data awal (dummy)",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "├── vendor/                          # Composer dependencies",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   ├── phpoffice/phpspreadsheet",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "│   └── tecnickcom/tcpdf",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "├── .htaccess                        # URL rewrite Apache",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "├── composer.json",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
              new Paragraph({
                children: [
                  new TextRun({
                    text: "└── index.php                        # Entry point aplikasi",
                    size: 18,
                    font: "Courier New",
                  }),
                ],
              }),
            ],
          }),
        ],
      }),
    ],
  }),
  pageBreak(),
];

// ============================================================
// BAB 8 - RENCANA PENGUJIAN
// ============================================================
const bab8 = [
  h1("BAB VIII   RENCANA PENGUJIAN DAN VALIDASI"),
  spacer(),
  h2("8.1  Skenario Pengujian Fungsional"),
  para(
    "Pengujian fungsional dilakukan menggunakan metode Black Box Testing untuk memverifikasi bahwa setiap modul sistem berjalan sesuai dengan spesifikasi yang telah ditetapkan.",
  ),
  spacer(),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [400, 2200, 2500, 2000, 2260],
    rows: [
      new TableRow({
        children: [
          tableCell("No", true, 1, 400, AlignmentType.CENTER),
          tableCell("Modul", true, 1, 2200),
          tableCell("Skenario Uji", true, 1, 2500),
          tableCell("Expected Result", true, 1, 2000),
          tableCell("Status", true, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("1", false, 1, 400, AlignmentType.CENTER),
          tableCell("Login", false, 1, 2200),
          tableCell("Input username & password benar", false, 1, 2500),
          tableCell("Redirect ke dashboard", false, 1, 2000),
          tableCell("□ Pass □ Fail", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("2", false, 1, 400, AlignmentType.CENTER),
          tableCell("Login", false, 1, 2200),
          tableCell("Input password salah 3 kali", false, 1, 2500),
          tableCell("Akun terkunci 15 menit", false, 1, 2000),
          tableCell("□ Pass □ Fail", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("3", false, 1, 400, AlignmentType.CENTER),
          tableCell("Input Destinasi", false, 1, 2200),
          tableCell("Isi semua field wajib, klik Simpan", false, 1, 2500),
          tableCell("Data tersimpan di MySQL, muncul di tabel", false, 1, 2000),
          tableCell("□ Pass □ Fail", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("4", false, 1, 400, AlignmentType.CENTER),
          tableCell("Input Destinasi", false, 1, 2200),
          tableCell("Upload foto > 2MB", false, 1, 2500),
          tableCell("Pesan error: ukuran file melebihi batas", false, 1, 2000),
          tableCell("□ Pass □ Fail", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("5", false, 1, 400, AlignmentType.CENTER),
          tableCell("Input Destinasi", false, 1, 2200),
          tableCell("Field koordinat kosong", false, 1, 2500),
          tableCell("Validasi gagal, pesan error per field", false, 1, 2000),
          tableCell("□ Pass □ Fail", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("6", false, 1, 400, AlignmentType.CENTER),
          tableCell("K-Means Engine", false, 1, 2200),
          tableCell("Jalankan clustering K=3, 87 data", false, 1, 2500),
          tableCell(
            "Hasil clustering tersimpan, peta terrender",
            false,
            1,
            2000,
          ),
          tableCell("□ Pass □ Fail", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("7", false, 1, 400, AlignmentType.CENTER),
          tableCell("K-Means Engine", false, 1, 2200),
          tableCell("K=1 (batas minimum)", false, 1, 2500),
          tableCell("Validasi: K minimal 2", false, 1, 2000),
          tableCell("□ Pass □ Fail", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("8", false, 1, 400, AlignmentType.CENTER),
          tableCell("Peta Interaktif", false, 1, 2200),
          tableCell("Klik marker destinasi pada peta", false, 1, 2500),
          tableCell("Popup info: nama, klaster, foto, nilai", false, 1, 2000),
          tableCell("□ Pass □ Fail", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("9", false, 1, 400, AlignmentType.CENTER),
          tableCell("Peta Interaktif", false, 1, 2200),
          tableCell("Filter klaster pada peta", false, 1, 2500),
          tableCell(
            "Hanya marker klaster terpilih yang tampil",
            false,
            1,
            2000,
          ),
          tableCell("□ Pass □ Fail", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("10", false, 1, 400, AlignmentType.CENTER),
          tableCell("Laporan", false, 1, 2200),
          tableCell("Klik Ekspor PDF", false, 1, 2500),
          tableCell(
            "File PDF ter-download berisi hasil clustering",
            false,
            1,
            2000,
          ),
          tableCell("□ Pass □ Fail", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("11", false, 1, 400, AlignmentType.CENTER),
          tableCell("Laporan", false, 1, 2200),
          tableCell("Klik Ekspor Excel", false, 1, 2500),
          tableCell(
            "File .xlsx ter-download dengan sheet lengkap",
            false,
            1,
            2000,
          ),
          tableCell("□ Pass □ Fail", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("12", false, 1, 400, AlignmentType.CENTER),
          tableCell("Role Access", false, 1, 2200),
          tableCell("User Viewer akses halaman admin", false, 1, 2500),
          tableCell("Redirect ke 403 Forbidden", false, 1, 2000),
          tableCell("□ Pass □ Fail", false, 1, 2260),
        ],
      }),
    ],
  }),
  spacer(),
  h2("8.2  Pengujian Akurasi Algoritma"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2500, 2000, 2400, 2460],
    rows: [
      new TableRow({
        children: [
          tableCell("Parameter Evaluasi", true, 1, 2500),
          tableCell("Metode", true, 1, 2000),
          tableCell("Target Nilai", true, 1, 2400),
          tableCell("Keterangan", true, 1, 2460),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Ketepatan Clustering", false, 1, 2500),
          tableCell("WSSE (Elbow Method)", false, 1, 2000),
          tableCell("Minimum / Elbow optimal", false, 1, 2400),
          tableCell(
            "Nilai WSSE menurun signifikan pada K optimal",
            false,
            1,
            2460,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Kualitas Klaster", false, 1, 2500),
          tableCell("Silhouette Coefficient", false, 1, 2000),
          tableCell("> 0.5 (good clustering)", false, 1, 2400),
          tableCell("Mendekati 1.0 = klaster sangat terpisah", false, 1, 2460),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Konvergensi Iterasi", false, 1, 2500),
          tableCell("Jumlah iterasi aktual", false, 1, 2000),
          tableCell("< 50 iterasi", false, 1, 2400),
          tableCell("Algoritma stabil dan efisien", false, 1, 2460),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Konsistensi Hasil", false, 1, 2500),
          tableCell("Repeated run test", false, 1, 2000),
          tableCell(">= 80% konsisten", false, 1, 2400),
          tableCell("10 kali percobaan dengan seed berbeda", false, 1, 2460),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Waktu Proses", false, 1, 2500),
          tableCell("Execution time PHP", false, 1, 2000),
          tableCell("< 10 detik (100 data)", false, 1, 2400),
          tableCell("Diukur dengan microtime() PHP", false, 1, 2460),
        ],
      }),
    ],
  }),
  spacer(),
  h2("8.3  Pengujian Performa Sistem"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2500, 2200, 2400, 2260],
    rows: [
      new TableRow({
        children: [
          tableCell("Aspek Performa", true, 1, 2500),
          tableCell("Tools Pengujian", true, 1, 2200),
          tableCell("Target KPI", true, 1, 2400),
          tableCell("Metode Ukur", true, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Page Load Time", false, 1, 2500),
          tableCell("Chrome DevTools / GTmetrix", false, 1, 2200),
          tableCell("< 3 detik", false, 1, 2400),
          tableCell("FCP & LCP metric", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Response Time API", false, 1, 2500),
          tableCell("Postman / cURL", false, 1, 2200),
          tableCell("< 2 detik", false, 1, 2400),
          tableCell("JSON response latency", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Render Peta (87 marker)", false, 1, 2500),
          tableCell("Leaflet DevTools", false, 1, 2200),
          tableCell("< 2 detik", false, 1, 2400),
          tableCell("Time to interactive", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Concurrent Users", false, 1, 2500),
          tableCell("Apache JMeter", false, 1, 2200),
          tableCell("10 user bersamaan", false, 1, 2400),
          tableCell("Stress test simulasi", false, 1, 2260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Memory Usage PHP", false, 1, 2500),
          tableCell("memory_get_peak_usage()", false, 1, 2200),
          tableCell("< 128 MB", false, 1, 2400),
          tableCell("Saat proses clustering", false, 1, 2260),
        ],
      }),
    ],
  }),
  spacer(),
  h2("8.4  Pengujian Pengguna (User Acceptance Testing)"),
  para(
    "Pengujian UAT dilakukan dengan melibatkan minimal 5 responden dari Dinas Pariwisata Kabupaten Magelang dan Bappeda. Penilaian menggunakan kuesioner System Usability Scale (SUS) dengan target skor > 70 (acceptable). Aspek yang dinilai meliputi kemudahan navigasi, kejelasan informasi peta, keterbacaan laporan, dan kecepatan respons sistem. Hasil UAT akan didokumentasikan sebagai bukti pengujian TKT 5.",
  ),
  pageBreak(),
];

// ============================================================
// BAB 9 - KEAMANAN SISTEM
// ============================================================
const bab9 = [
  h1("BAB IX   KEAMANAN SISTEM"),
  spacer(),
  h2("9.1  Implementasi Keamanan"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [2800, 6560],
    rows: [
      new TableRow({
        children: [
          tableCell("Aspek Keamanan", true, 1, 2800),
          tableCell("Implementasi dalam PHP", true, 1, 6560),
        ],
      }),
      new TableRow({
        children: [
          tableCell("SQL Injection Prevention", false, 1, 2800),
          tableCell(
            "Seluruh query menggunakan PDO Prepared Statement dengan binding parameter. Tidak ada query langsung dengan string concatenation.",
            false,
            1,
            6560,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("XSS Prevention", false, 1, 2800),
          tableCell(
            "Semua output ke browser dilewatkan htmlspecialchars(). Input dari form disanitasi sebelum diproses.",
            false,
            1,
            6560,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("CSRF Protection", false, 1, 2800),
          tableCell(
            "Token CSRF dibuat per sesi dan diverifikasi pada setiap POST request menggunakan hidden field _token.",
            false,
            1,
            6560,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Password Security", false, 1, 2800),
          tableCell(
            "Password disimpan menggunakan password_hash() dengan algoritma BCRYPT. Verifikasi menggunakan password_verify().",
            false,
            1,
            6560,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Session Management", false, 1, 2800),
          tableCell(
            "Session PHP dengan konfigurasi: session.cookie_httponly=1, session.cookie_secure=1, session_regenerate_id() saat login.",
            false,
            1,
            6560,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("File Upload Security", false, 1, 2800),
          tableCell(
            "Validasi MIME type, ekstensi file (whitelist), ukuran maksimal 2MB, rename file acak agar tidak dapat diprediksi.",
            false,
            1,
            6560,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Access Control", false, 1, 2800),
          tableCell(
            "Middleware role-based: setiap controller memeriksa role user dari session sebelum mengeksekusi aksi.",
            false,
            1,
            6560,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Error Handling", false, 1, 2800),
          tableCell(
            "Mode production: error tidak ditampilkan ke browser, semua error ditulis ke log file. Mode development: detail error aktif.",
            false,
            1,
            6560,
          ),
        ],
      }),
    ],
  }),
  pageBreak(),
];

// ============================================================
// BAB 10 - IMPLEMENTASI & DEPLOYMENT
// ============================================================
const bab10 = [
  h1("BAB X   RENCANA IMPLEMENTASI DAN DEPLOYMENT"),
  spacer(),
  h2("10.1  Tahapan Implementasi"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [600, 3000, 2500, 3260],
    rows: [
      new TableRow({
        children: [
          tableCell("No", true, 1, 600, AlignmentType.CENTER),
          tableCell("Kegiatan", true, 1, 3000),
          tableCell("Durasi", true, 1, 2500),
          tableCell("Output", true, 1, 3260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("1", false, 1, 600, AlignmentType.CENTER),
          tableCell(
            "Setup environment & instalasi dependensi Composer",
            false,
            1,
            3000,
          ),
          tableCell("1 minggu (Juli 2026)", false, 1, 2500),
          tableCell("Lingkungan dev siap (XAMPP/Docker)", false, 1, 3260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("2", false, 1, 600, AlignmentType.CENTER),
          tableCell(
            "Pengumpulan & kurasi data destinasi Kab. Magelang",
            false,
            1,
            3000,
          ),
          tableCell("2 minggu (Juli–Agt)", false, 1, 2500),
          tableCell("Dataset 87+ destinasi, 5 kriteria terisi", false, 1, 3260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("3", false, 1, 600, AlignmentType.CENTER),
          tableCell(
            "Implementasi database MySQL (schema + seeder)",
            false,
            1,
            3000,
          ),
          tableCell("1 minggu (Agustus)", false, 1, 2500),
          tableCell("Database siap pakai dengan data awal", false, 1, 3260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("4", false, 1, 600, AlignmentType.CENTER),
          tableCell(
            "Pengembangan modul autentikasi & manajemen user",
            false,
            1,
            3000,
          ),
          tableCell("1 minggu (Agustus)", false, 1, 2500),
          tableCell("Login/logout/role berfungsi", false, 1, 3260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("5", false, 1, 600, AlignmentType.CENTER),
          tableCell("Pengembangan CRUD destinasi & kriteria", false, 1, 3000),
          tableCell("2 minggu (Agustus–Sep)", false, 1, 2500),
          tableCell("Manajemen data wisata berfungsi", false, 1, 3260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("6", false, 1, 600, AlignmentType.CENTER),
          tableCell("Implementasi K-Means Engine (class PHP)", false, 1, 3000),
          tableCell("2 minggu (September)", false, 1, 2500),
          tableCell("Algoritma clustering teruji dan akurat", false, 1, 3260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("7", false, 1, 600, AlignmentType.CENTER),
          tableCell(
            "Integrasi Leaflet.js untuk peta digital interaktif",
            false,
            1,
            3000,
          ),
          tableCell("2 minggu (Oktober)", false, 1, 2500),
          tableCell(
            "Peta cluster berfungsi dengan marker warna",
            false,
            1,
            3260,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("8", false, 1, 600, AlignmentType.CENTER),
          tableCell(
            "Modul laporan: PDF (TCPDF) dan Excel (PhpSpreadsheet)",
            false,
            1,
            3000,
          ),
          tableCell("1 minggu (Oktober)", false, 1, 2500),
          tableCell("Ekspor laporan berjalan", false, 1, 3260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("9", false, 1, 600, AlignmentType.CENTER),
          tableCell("Pengujian fungsional, performa, dan UAT", false, 1, 3000),
          tableCell("3 minggu (Nov 2026)", false, 1, 2500),
          tableCell("Laporan uji TKT 5 tersertifikasi", false, 1, 3260),
        ],
      }),
      new TableRow({
        children: [
          tableCell("10", false, 1, 600, AlignmentType.CENTER),
          tableCell(
            "Deployment ke server lokal Dinas Pariwisata",
            false,
            1,
            3000,
          ),
          tableCell("1 minggu (Desember)", false, 1, 2500),
          tableCell(
            "Sistem live di server LAN Dinas Pariwisata",
            false,
            1,
            3260,
          ),
        ],
      }),
      new TableRow({
        children: [
          tableCell("11", false, 1, 600, AlignmentType.CENTER),
          tableCell(
            "Penyusunan dokumentasi teknis & laporan akhir",
            false,
            1,
            3000,
          ),
          tableCell("2 minggu (Desember)", false, 1, 2500),
          tableCell("Dokumen teknis, manual pengguna", false, 1, 3260),
        ],
      }),
    ],
  }),
  spacer(),
  h2("10.2  Spesifikasi Server Minimum"),
  new Table({
    width: { size: 9360, type: WidthType.DXA },
    columnWidths: [3000, 3180, 3180],
    rows: [
      new TableRow({
        children: [
          tableCell("Komponen", true, 1, 3000),
          tableCell("Spesifikasi Minimum", true, 1, 3180),
          tableCell("Spesifikasi Rekomendasi", true, 1, 3180),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Sistem Operasi", false, 1, 3000),
          tableCell("Ubuntu 20.04 LTS / Windows Server 2019", false, 1, 3180),
          tableCell("Ubuntu 22.04 LTS", false, 1, 3180),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Web Server", false, 1, 3000),
          tableCell("Apache 2.4 / Nginx 1.18", false, 1, 3180),
          tableCell("Nginx 1.24 dengan SSL (HTTPS)", false, 1, 3180),
        ],
      }),
      new TableRow({
        children: [
          tableCell("PHP Version", false, 1, 3000),
          tableCell("PHP 8.0", false, 1, 3180),
          tableCell("PHP 8.2 + OPcache enabled", false, 1, 3180),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Database", false, 1, 3000),
          tableCell("MySQL 8.0 / MariaDB 10.6", false, 1, 3180),
          tableCell("MySQL 8.0 dengan tuning innodb_buffer", false, 1, 3180),
        ],
      }),
      new TableRow({
        children: [
          tableCell("RAM", false, 1, 3000),
          tableCell("4 GB", false, 1, 3180),
          tableCell("8 GB", false, 1, 3180),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Storage", false, 1, 3000),
          tableCell("50 GB SSD", false, 1, 3180),
          tableCell("100 GB SSD", false, 1, 3180),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Processor", false, 1, 3000),
          tableCell("2 Core CPU", false, 1, 3180),
          tableCell("4 Core CPU", false, 1, 3180),
        ],
      }),
      new TableRow({
        children: [
          tableCell("Koneksi Jaringan", false, 1, 3000),
          tableCell("LAN intranet Dinas Pariwisata", false, 1, 3180),
          tableCell("LAN + akses internet untuk tile peta", false, 1, 3180),
        ],
      }),
    ],
  }),
  pageBreak(),
];

// ============================================================
// BAB 11 - PENUTUP
// ============================================================
const bab11 = [
  h1("BAB XI   PENUTUP"),
  spacer(),
  h2("11.1  Kesimpulan Blueprint"),
  para(
    "Dokumen Blueprint ini telah menyajikan rancangan teknis lengkap Prototipe Sistem Cerdas Pemetaan Potensi Wisata berbasis K-Means Clustering untuk Kabupaten Magelang. Sistem yang dikembangkan menggunakan teknologi PHP 8.1 sebagai backend, MySQL 8.0 sebagai basis data, Bootstrap 5 untuk antarmuka responsif, Leaflet.js untuk visualisasi peta digital interaktif, dan Chart.js untuk analitik grafis.",
  ),
  para(
    "Blueprint ini mencakup: arsitektur three-tier sistem, skema basis data relasional dengan 7 tabel utama, diagram ERD dan flowchart alur kerja lengkap, pseudocode dan implementasi algoritma K-Means Clustering, wireframe antarmuka 6 halaman utama, struktur direktori proyek MVC, rencana pengujian fungsional dan performa, serta strategi deployment di lingkungan Dinas Pariwisata Kabupaten Magelang.",
  ),
  spacer(),
  h2("11.2  Indikator Keberhasilan TKT 5"),
  para(
    "Prototipe dinyatakan mencapai TKT 5 apabila memenuhi seluruh indikator berikut:",
  ),
  bullet(
    "Sistem berjalan stabil pada server lokal Dinas Pariwisata tanpa error kritis",
  ),
  bullet("Algoritma K-Means menghasilkan nilai Silhouette Coefficient >= 0.5"),
  bullet("Seluruh 11 skenario pengujian fungsional dinyatakan PASS"),
  bullet(
    "Hasil UAT mendapatkan skor SUS >= 70 dari minimal 5 responden stakeholder",
  ),
  bullet("Proses clustering 87 data selesai dalam waktu < 10 detik"),
  bullet(
    "Peta digital interaktif menampilkan seluruh marker klaster dengan benar",
  ),
  bullet("Laporan PDF dan Excel berhasil diekspor dengan data yang akurat"),
  bullet(
    "Dokumentasi teknis lengkap tersedia: manual pengguna, laporan uji, source code",
  ),
  spacer(),
  h2("11.3  Pengembangan Lanjutan (TKT 6-7)"),
  para(
    "Setelah pencapaian TKT 5, sistem akan dikembangkan lebih lanjut menuju TKT 6-7 pada tahun 2027 dengan rencana penambahan fitur:",
  ),
  bullet(
    "Integrasi API data wisata real-time dari platform digital pariwisata nasional",
  ),
  bullet("Implementasi antarmuka mobile-first atau Progressive Web App (PWA)"),
  bullet("Fitur rekomendasi destinasi berbasis klaster untuk wisatawan"),
  bullet("Dashboard analitik lanjutan dengan prediksi tren kunjungan"),
  bullet(
    "Integrasi SSO (Single Sign-On) dengan SIAK atau sistem pemerintahan daerah",
  ),
  bullet("Migrasi ke cloud server untuk aksesibilitas lebih luas"),
  spacer(),
  spacer(),
  new Paragraph({
    alignment: AlignmentType.CENTER,
    children: [
      new TextRun({
        text: "─────────────────────────────────────────────────────",
        size: 22,
        font: "Arial",
        color: BLUE_MID,
      }),
    ],
    spacing: { before: 200, after: 100 },
  }),
  new Paragraph({
    alignment: AlignmentType.CENTER,
    children: [
      new TextRun({
        text: "Dokumen Blueprint ini merupakan panduan teknis utama pengembangan prototipe.",
        size: 20,
        font: "Arial",
        italics: true,
        color: BLUE_DARK,
      }),
    ],
    spacing: { before: 0, after: 60 },
  }),
  new Paragraph({
    alignment: AlignmentType.CENTER,
    children: [
      new TextRun({
        text: "Program Hilirisasi Riset Prioritas – Pengujian Model dan Prototipe Tahun 2026",
        size: 20,
        font: "Arial",
        italics: true,
        color: BLUE_DARK,
      }),
    ],
    spacing: { before: 0, after: 60 },
  }),
  new Paragraph({
    alignment: AlignmentType.CENTER,
    children: [
      new TextRun({
        text: "Kabupaten Magelang, Jawa Tengah",
        size: 20,
        font: "Arial",
        color: BLUE_DARK,
      }),
    ],
    spacing: { before: 0, after: 60 },
  }),
];

// ============================================================
// BUILD DOCUMENT
// ============================================================
const doc = new Document({
  numbering: {
    config: [
      {
        reference: "bullets",
        levels: [
          {
            level: 0,
            format: LevelFormat.BULLET,
            text: "•",
            alignment: AlignmentType.LEFT,
            style: { paragraph: { indent: { left: 720, hanging: 360 } } },
          },
        ],
      },
      {
        reference: "numbers",
        levels: [
          {
            level: 0,
            format: LevelFormat.DECIMAL,
            text: "%1.",
            alignment: AlignmentType.LEFT,
            style: { paragraph: { indent: { left: 720, hanging: 360 } } },
          },
        ],
      },
    ],
  },
  styles: {
    default: {
      document: { run: { font: "Times New Roman", size: 22 } },
    },
    paragraphStyles: [
      {
        id: "Heading1",
        name: "Heading 1",
        basedOn: "Normal",
        next: "Normal",
        quickFormat: true,
        run: { size: 32, bold: true, font: "Arial", color: WHITE },
        paragraph: { spacing: { before: 360, after: 200 }, outlineLevel: 0 },
      },
      {
        id: "Heading2",
        name: "Heading 2",
        basedOn: "Normal",
        next: "Normal",
        quickFormat: true,
        run: { size: 26, bold: true, font: "Arial", color: WHITE },
        paragraph: { spacing: { before: 280, after: 140 }, outlineLevel: 1 },
      },
      {
        id: "Heading3",
        name: "Heading 3",
        basedOn: "Normal",
        next: "Normal",
        quickFormat: true,
        run: { size: 24, bold: true, font: "Arial", color: BLUE_DARK },
        paragraph: { spacing: { before: 200, after: 100 }, outlineLevel: 2 },
      },
    ],
  },
  sections: [
    {
      properties: {
        page: {
          size: { width: 11906, height: 16838 },
          margin: { top: 1440, right: 1260, bottom: 1440, left: 1440 },
        },
      },
      headers: {
        default: new Header({
          children: [
            new Paragraph({
              children: [
                new TextRun({
                  text: "BLUEPRINT PROTOTIPE – Sistem Cerdas Pemetaan Potensi Wisata K-Means Clustering – Kab. Magelang",
                  size: 16,
                  font: "Arial",
                  color: BLUE_DARK,
                  italics: true,
                }),
              ],
              border: {
                bottom: {
                  style: BorderStyle.SINGLE,
                  size: 4,
                  color: BLUE_MID,
                  space: 2,
                },
              },
            }),
          ],
        }),
      },
      footers: {
        default: new Footer({
          children: [
            new Paragraph({
              alignment: AlignmentType.CENTER,
              border: {
                top: {
                  style: BorderStyle.SINGLE,
                  size: 4,
                  color: BLUE_MID,
                  space: 2,
                },
              },
              children: [
                new TextRun({ text: "Halaman ", size: 16, font: "Arial" }),
                new TextRun({
                  children: [PageNumber.CURRENT],
                  size: 16,
                  font: "Arial",
                }),
                new TextRun({
                  text: " | Program Hilirisasi Riset Prioritas 2026 | Kabupaten Magelang",
                  size: 16,
                  font: "Arial",
                  color: "666666",
                }),
              ],
            }),
          ],
        }),
      },
      children: [
        ...coverPage,
        ...bab1,
        ...bab2,
        ...bab3,
        ...bab4,
        ...bab5,
        ...bab6,
        ...bab7,
        ...bab8,
        ...bab9,
        ...bab10,
        ...bab11,
      ],
    },
  ],
});

Packer.toBuffer(doc).then((buf) => {
  fs.writeFileSync("Blueprint_SmartTourism_KMeans_Magelang.docx", buf);
  console.log("Done!");
});
