// Selada UI & Component Kit Generator Plugin
figma.showUI(__html__, { width: 340, height: 320 });

// Color Tokens (Fun Theme)
const COLORS = {
  purple: { r: 124 / 255, g: 58 / 255, b: 237 / 255 },     // #7C3AED
  lavender: { r: 237 / 255, g: 233 / 255, b: 254 / 255 }, // #EDE9FE
  pink: { r: 255 / 255, g: 77 / 255, b: 141 / 255 },       // #FF4D8D
  bubble: { r: 255 / 255, g: 184 / 255, b: 210 / 255 },     // #FFB8D2
  yellow: { r: 250 / 255, g: 204 / 255, b: 21 / 255 },     // #FACC15
  cream: { r: 254 / 255, g: 249 / 255, b: 195 / 255 },     // #FEF9C3
  mint: { r: 16 / 255, g: 185 / 255, b: 129 / 255 },       // #10B981
  mintSoft: { r: 209 / 255, g: 250 / 255, b: 229 / 255 }, // #D1FAE5
  blue: { r: 59 / 255, g: 130 / 255, b: 246 / 255 },       // #3B82F6
  blueSoft: { r: 219 / 255, g: 234 / 255, b: 254 / 255 }, // #DBEAFE
  rose: { r: 244 / 255, g: 63 / 255, b: 94 / 255 },        // #F43F5E
  roseSoft: { r: 255 / 255, g: 228 / 255, b: 230 / 255 }, // #FFE4E6
  dark: { r: 24 / 255, g: 24 / 255, b: 27 / 255 },         // #18181B
  grayBg: { r: 248 / 255, g: 247 / 255, b: 255 / 255 },   // #F8F7FF
  white: { r: 1, g: 1, b: 1 },
  gray500: { r: 107 / 255, g: 114 / 255, b: 128 / 255 },
  gray200: { r: 229 / 255, g: 231 / 255, b: 235 / 255 },
};

// Helper: Stroke border
function applyBrutalBorder(node, color = COLORS.dark, weight = 2) {
  node.strokes = [{ type: 'SOLID', color: color }];
  node.strokeWeight = weight;
  node.strokeAlign = 'INSIDE';
}

// Helper: Text node
function makeText(text, size = 13, weight = 'Regular', color = COLORS.dark) {
  const t = figma.createText();
  t.fontName = { family: 'Inter', style: weight };
  t.characters = text;
  t.fontSize = size;
  t.fills = [{ type: 'SOLID', color: color }];
  return t;
}

// Helper: Auto Layout container
function makeAutoLayout(name, dir = 'VERTICAL', spacing = 8, pad = 0, bg = null, radius = 0) {
  const f = figma.createFrame();
  f.name = name;
  f.layoutMode = dir;
  f.primaryAxisSizingMode = 'AUTO';
  f.counterAxisSizingMode = 'AUTO';
  f.itemSpacing = spacing;
  f.paddingTop = pad;
  f.paddingBottom = pad;
  f.paddingLeft = pad;
  f.paddingRight = pad;
  f.cornerRadius = radius;
  if (bg) {
    f.fills = [{ type: 'SOLID', color: bg }];
  } else {
    f.fills = [];
  }
  return f;
}

figma.ui.onmessage = async (msg) => {
  if (msg.type === 'generate') {
    // 1. Load Fonts
    await figma.loadFontAsync({ family: 'Inter', style: 'Regular' });
    await figma.loadFontAsync({ family: 'Inter', style: 'Medium' });
    await figma.loadFontAsync({ family: 'Inter', style: 'Semi Bold' });
    await figma.loadFontAsync({ family: 'Inter', style: 'Bold' });

    let currentX = 0;
    const currentY = 0;

    // ──────────────────────────────────────────
    // A. DESIGN TOKENS & COMPONENTS KIT
    // ──────────────────────────────────────────
    if (msg.tokens) {
      const kitFrame = makeAutoLayout('🎨 Design Tokens & Components (Fun Theme)', 'VERTICAL', 24, 32, COLORS.white, 24);
      applyBrutalBorder(kitFrame);
      kitFrame.x = currentX;
      kitFrame.y = currentY;

      // Header
      const kitHeader = makeAutoLayout('Kit Header', 'VERTICAL', 4);
      kitHeader.appendChild(makeText('🎨 SELADA FM DESIGN SYSTEM', 22, 'Bold', COLORS.purple));
      kitHeader.appendChild(makeText('Tokens, Colors, Badges, & Master Components (Auto Layout)', 13, 'Regular', COLORS.gray500));
      kitFrame.appendChild(kitHeader);

      // Section 1: Color Swatches
      const swatchSection = makeAutoLayout('Colors Swatch Section', 'VERTICAL', 10);
      swatchSection.appendChild(makeText('1. PALET WARNA (FUN THEME)', 14, 'Bold', COLORS.dark));

      const swatchesRow = makeAutoLayout('Swatches Row', 'HORIZONTAL', 12);
      const colorDefs = [
        { name: 'Electric Purple', hex: '#7C3AED', color: COLORS.purple, textCol: COLORS.white },
        { name: 'Pop Pink', hex: '#FF4D8D', color: COLORS.pink, textCol: COLORS.white },
        { name: 'Sun Yellow', hex: '#FACC15', color: COLORS.yellow, textCol: COLORS.dark },
        { name: 'Fresh Mint', hex: '#10B981', color: COLORS.mint, textCol: COLORS.white },
        { name: 'Soft Lavender', hex: '#EDE9FE', color: COLORS.lavender, textCol: COLORS.purple },
        { name: 'Charcoal Dark', hex: '#18181B', color: COLORS.dark, textCol: COLORS.white },
      ];

      for (const c of colorDefs) {
        const sw = makeAutoLayout(c.name, 'VERTICAL', 4, 10, c.color, 12);
        sw.resize(110, 80);
        applyBrutalBorder(sw);
        sw.appendChild(makeText(c.name, 11, 'Bold', c.textCol));
        sw.appendChild(makeText(c.hex, 10, 'Regular', c.textCol));
        swatchesRow.appendChild(sw);
      }
      swatchSection.appendChild(swatchesRow);
      kitFrame.appendChild(swatchSection);

      // Section 2: Badges
      const badgeSection = makeAutoLayout('Badges Section', 'VERTICAL', 10);
      badgeSection.appendChild(makeText('2. STATUS BADGES', 14, 'Bold', COLORS.dark));

      const badgesRow = makeAutoLayout('Badges Row', 'HORIZONTAL', 8);
      const badgeDefs = [
        { label: '⏳ Menunggu', bg: COLORS.cream, fg: COLORS.dark },
        { label: '✓ Disetujui OSIS', bg: COLORS.blueSoft, fg: COLORS.blue },
        { label: '▶ ON AIR (Diputar)', bg: COLORS.mintSoft, fg: COLORS.mint },
        { label: '✗ Ditolak', bg: COLORS.roseSoft, fg: COLORS.rose },
        { label: '🔥 Top Populer', bg: COLORS.yellow, fg: COLORS.dark },
      ];

      for (const b of badgeDefs) {
        const badge = makeAutoLayout(b.label, 'HORIZONTAL', 4, 6, b.bg, 100);
        applyBrutalBorder(badge, COLORS.dark, 1.5);
        badge.paddingLeft = 10;
        badge.paddingRight = 10;
        badge.appendChild(makeText(b.label, 11, 'Bold', b.fg));
        badgesRow.appendChild(badge);
      }
      badgeSection.appendChild(badgesRow);
      kitFrame.appendChild(badgeSection);

      // Section 3: Buttons
      const buttonSection = makeAutoLayout('Buttons Section', 'VERTICAL', 10);
      buttonSection.appendChild(makeText('3. ACTION BUTTONS', 14, 'Bold', COLORS.dark));

      const btnRow = makeAutoLayout('Buttons Row', 'HORIZONTAL', 12);

      const btn1 = makeAutoLayout('Button / Primary', 'HORIZONTAL', 6, 10, COLORS.purple, 12);
      applyBrutalBorder(btn1);
      btn1.paddingLeft = 16;
      btn1.paddingRight = 16;
      btn1.appendChild(makeText('🚀 Kirim Request', 12, 'Bold', COLORS.white));
      btnRow.appendChild(btn1);

      const btn2 = makeAutoLayout('Button / Accent', 'HORIZONTAL', 6, 10, COLORS.pink, 12);
      applyBrutalBorder(btn2);
      btn2.paddingLeft = 16;
      btn2.paddingRight = 16;
      btn2.appendChild(makeText('❤️ Vote Lagu', 12, 'Bold', COLORS.white));
      btnRow.appendChild(btn2);

      const btn3 = makeAutoLayout('Button / Like Vote', 'HORIZONTAL', 4, 8, COLORS.lavender, 12);
      applyBrutalBorder(btn3);
      btn3.paddingLeft = 12;
      btn3.paddingRight = 12;
      btn3.appendChild(makeText('❤️ 14', 12, 'Bold', COLORS.purple));
      btnRow.appendChild(btn3);

      buttonSection.appendChild(btnRow);
      kitFrame.appendChild(buttonSection);

      figma.currentPage.appendChild(kitFrame);
      currentX += kitFrame.width + 60;
    }

    // ──────────────────────────────────────────
    // B. MOBILE SISWA SCREENS (390 x 844 px)
    // ──────────────────────────────────────────
    if (msg.mobile) {
      // SCREEN 1: Antrian & ON AIR
      const phone1 = makeAutoLayout('📱 Mobile - 1. Antrian & Live ON AIR', 'VERTICAL', 12, 16, COLORS.grayBg, 36);
      phone1.resize(390, 844);
      applyBrutalBorder(phone1, COLORS.dark, 3);
      phone1.x = currentX;
      phone1.y = currentY;

      // Top Status Bar
      const statusBar = makeAutoLayout('Status Bar', 'HORIZONTAL', 0, 4);
      statusBar.layoutSizingHorizontal = 'FILL';
      statusBar.primaryAxisAlignItems = 'SPACE_BETWEEN';
      statusBar.appendChild(makeText('09:41', 12, 'Bold', COLORS.dark));
      statusBar.appendChild(makeText('5G  100%', 11, 'Semi Bold', COLORS.dark));
      phone1.appendChild(statusBar);

      // App Header
      const appHeader = makeAutoLayout('App Header', 'HORIZONTAL', 8, 8, COLORS.white, 16);
      applyBrutalBorder(appHeader, COLORS.dark, 2);
      appHeader.layoutSizingHorizontal = 'FILL';
      appHeader.primaryAxisAlignItems = 'SPACE_BETWEEN';

      const logoBox = makeAutoLayout('Logo & Name', 'HORIZONTAL', 8);
      logoBox.appendChild(makeText('📻', 18));
      const titleBox = makeAutoLayout('Titles', 'VERTICAL', 0);
      titleBox.appendChild(makeText('SELADA FM', 14, 'Bold', COLORS.purple));
      titleBox.appendChild(makeText('Radio Request Siswa', 10, 'Regular', COLORS.gray500));
      logoBox.appendChild(titleBox);
      appHeader.appendChild(logoBox);

      const quotaBadge = makeAutoLayout('Quota Badge', 'HORIZONTAL', 4, 4, COLORS.yellow, 100);
      applyBrutalBorder(quotaBadge, COLORS.dark, 1);
      quotaBadge.paddingLeft = 8;
      quotaBadge.paddingRight = 8;
      quotaBadge.appendChild(makeText('Kuota: 1/1 🎧', 10, 'Bold', COLORS.dark));
      appHeader.appendChild(quotaBadge);

      phone1.appendChild(appHeader);

      // Hero ON AIR Card
      const heroCard = makeAutoLayout('Card / ON AIR Live', 'VERTICAL', 10, 14, COLORS.purple, 20);
      applyBrutalBorder(heroCard, COLORS.dark, 2);
      heroCard.layoutSizingHorizontal = 'FILL';

      const livePill = makeAutoLayout('Live Pill', 'HORIZONTAL', 4, 4, COLORS.rose, 100);
      livePill.paddingLeft = 8;
      livePill.paddingRight = 8;
      livePill.appendChild(makeText('● ON AIR DI BEL SEKOLAH', 10, 'Bold', COLORS.white));
      heroCard.appendChild(livePill);

      const songInfo = makeAutoLayout('Song Info Row', 'HORIZONTAL', 10);
      const vinyl = makeAutoLayout('Vinyl', 'HORIZONTAL', 0, 0, COLORS.dark, 100);
      vinyl.resize(48, 48);
      vinyl.primaryAxisAlignItems = 'CENTER';
      vinyl.counterAxisAlignItems = 'CENTER';
      vinyl.appendChild(makeText('🎵', 16));
      songInfo.appendChild(vinyl);

      const songText = makeAutoLayout('Song Texts', 'VERTICAL', 2);
      songText.appendChild(makeText('Ghea Indrawari — Jiwa yang Bersedih', 13, 'Bold', COLORS.white));
      songText.appendChild(makeText('Req: Alif (XII RPL 1)', 11, 'Regular', COLORS.lavender));
      songInfo.appendChild(songText);
      heroCard.appendChild(songInfo);

      // Shoutout Dedication Box
      const shoutBox = makeAutoLayout('Dedication Box', 'VERTICAL', 2, 8, COLORS.lavender, 12);
      applyBrutalBorder(shoutBox, COLORS.dark, 1);
      shoutBox.layoutSizingHorizontal = 'FILL';
      shoutBox.appendChild(makeText('📢 Salam Dedikasi:', 10, 'Bold', COLORS.purple));
      shoutBox.appendChild(makeText('"Semangat buat XII RPL yang lagi persiapan ujian!"', 11, 'Medium', COLORS.dark));
      heroCard.appendChild(shoutBox);

      phone1.appendChild(heroCard);

      // Antrian Title Row
      const queueTitleRow = makeAutoLayout('Queue Title Row', 'HORIZONTAL', 0);
      queueTitleRow.layoutSizingHorizontal = 'FILL';
      queueTitleRow.primaryAxisAlignItems = 'SPACE_BETWEEN';
      queueTitleRow.appendChild(makeText('🎶 Antrian Lagu Berikutnya', 13, 'Bold', COLORS.dark));
      queueTitleRow.appendChild(makeText('Update Live', 10, 'Semi Bold', COLORS.gray500));
      phone1.appendChild(queueTitleRow);

      // Song Queue List
      const songs = [
        { rank: '#1', title: 'Sal Priadi — Dari planet lain', requester: 'Nabila (XI MIPA 2)', votes: '12', status: '✓ Disetujui' },
        { rank: '#2', title: 'Coldplay — Viva La Vida', requester: 'Budi (X TKJ 3)', votes: '8', status: '🔥 Populer' },
        { rank: '#3', title: 'Tulus — Tujuh Belas', requester: 'Cindy (XII AK)', votes: '3', status: '⏳ Menunggu' },
      ];

      for (const s of songs) {
        const item = makeAutoLayout('Song Item Card', 'HORIZONTAL', 8, 10, COLORS.white, 16);
        applyBrutalBorder(item, COLORS.dark, 1.5);
        item.layoutSizingHorizontal = 'FILL';
        item.primaryAxisAlignItems = 'SPACE_BETWEEN';

        const leftCol = makeAutoLayout('Left', 'HORIZONTAL', 8);
        leftCol.counterAxisAlignItems = 'CENTER';
        leftCol.appendChild(makeText(s.rank, 12, 'Bold', COLORS.purple));

        const thumb = makeAutoLayout('Thumb', 'HORIZONTAL', 0, 0, COLORS.lavender, 8);
        thumb.resize(42, 32);
        applyBrutalBorder(thumb, COLORS.dark, 1);
        leftCol.appendChild(thumb);

        const colText = makeAutoLayout('Text', 'VERTICAL', 2);
        colText.appendChild(makeText(s.title, 11, 'Bold', COLORS.dark));
        colText.appendChild(makeText(s.requester, 10, 'Regular', COLORS.gray500));
        leftCol.appendChild(colText);
        item.appendChild(leftCol);

        const voteBtn = makeAutoLayout('Vote Btn', 'VERTICAL', 2, 6, COLORS.lavender, 10);
        applyBrutalBorder(voteBtn, COLORS.dark, 1);
        voteBtn.appendChild(makeText('❤️ ' + s.votes, 10, 'Bold', COLORS.purple));
        item.appendChild(voteBtn);

        phone1.appendChild(item);
      }

      // Bottom Navigation
      const bottomNav = makeAutoLayout('Bottom Navigation', 'HORIZONTAL', 0, 10, COLORS.white, 20);
      applyBrutalBorder(bottomNav, COLORS.dark, 2);
      bottomNav.layoutSizingHorizontal = 'FILL';
      bottomNav.primaryAxisAlignItems = 'SPACE_AROUND';
      bottomNav.appendChild(makeText('📻 Antrian', 10, 'Bold', COLORS.purple));
      bottomNav.appendChild(makeText('➕ Request', 10, 'Medium', COLORS.gray500));
      bottomNav.appendChild(makeText('🙋‍♂️ Status', 10, 'Medium', COLORS.gray500));
      bottomNav.appendChild(makeText('💬 Saran', 10, 'Medium', COLORS.gray500));
      phone1.appendChild(bottomNav);

      figma.currentPage.appendChild(phone1);
      currentX += phone1.width + 40;

      // SCREEN 2: Form Request Lagu
      const phone2 = makeAutoLayout('📱 Mobile - 2. Form Request Lagu', 'VERTICAL', 14, 16, COLORS.grayBg, 36);
      phone2.resize(390, 844);
      applyBrutalBorder(phone2, COLORS.dark, 3);
      phone2.x = currentX;
      phone2.y = currentY;

      // Header Back
      const backNav = makeAutoLayout('Back Nav', 'HORIZONTAL', 8, 4);
      backNav.appendChild(makeText('← Kembali ke Antrian', 12, 'Bold', COLORS.purple));
      phone2.appendChild(backNav);

      const formCard = makeAutoLayout('Form Request Container', 'VERTICAL', 12, 16, COLORS.white, 20);
      applyBrutalBorder(formCard, COLORS.dark, 2);
      formCard.layoutSizingHorizontal = 'FILL';

      formCard.appendChild(makeText('➕ Request Lagu Favorit', 16, 'Bold', COLORS.dark));
      formCard.appendChild(makeText('Tempel tautan YouTube/YouTube Music untuk jam istirahat.', 11, 'Regular', COLORS.gray500));

      // Input 1: URL
      const field1 = makeAutoLayout('Field URL', 'VERTICAL', 4);
      field1.layoutSizingHorizontal = 'FILL';
      field1.appendChild(makeText('Link / ID YouTube', 11, 'Bold', COLORS.dark));
      const inp1 = makeAutoLayout('Input Box', 'HORIZONTAL', 8, 10, COLORS.grayBg, 12);
      applyBrutalBorder(inp1, COLORS.dark, 1.5);
      inp1.layoutSizingHorizontal = 'FILL';
      inp1.appendChild(makeText('https://youtu.be/dQw4w9WgXcQ', 11, 'Regular', COLORS.dark));
      field1.appendChild(inp1);
      formCard.appendChild(field1);

      // Auto Preview Box
      const previewCard = makeAutoLayout('Live Preview Box', 'HORIZONTAL', 8, 8, COLORS.lavender, 12);
      applyBrutalBorder(previewCard, COLORS.dark, 1.5);
      previewCard.layoutSizingHorizontal = 'FILL';
      const pThumb = makeAutoLayout('Thumb', 'HORIZONTAL', 0, 0, COLORS.white, 8);
      pThumb.resize(48, 36);
      applyBrutalBorder(pThumb, COLORS.dark, 1);
      previewCard.appendChild(pThumb);
      const pText = makeAutoLayout('Preview Text', 'VERTICAL', 2);
      pText.appendChild(makeText('Never Gonna Give You Up', 11, 'Bold', COLORS.dark));
      pText.appendChild(makeText('Rick Astley • 03:32 ✓ Terdeteksi', 10, 'Regular', COLORS.purple));
      previewCard.appendChild(pText);
      formCard.appendChild(previewCard);

      // Input 2: Salam Dedikasi
      const field2 = makeAutoLayout('Field Dedikasi', 'VERTICAL', 4);
      field2.layoutSizingHorizontal = 'FILL';
      field2.appendChild(makeText('Pesan Salam / Dedikasi (Opsional)', 11, 'Bold', COLORS.dark));
      const inp2 = makeAutoLayout('Textarea Box', 'VERTICAL', 4, 10, COLORS.grayBg, 12);
      applyBrutalBorder(inp2, COLORS.dark, 1.5);
      inp2.layoutSizingHorizontal = 'FILL';
      inp2.appendChild(makeText('Semangat buat teman-teman kelas XII, sukses bareng!', 11, 'Regular', COLORS.dark));
      field2.appendChild(inp2);
      formCard.appendChild(field2);

      // Submit Button
      const submitBtn = makeAutoLayout('Submit Button', 'HORIZONTAL', 6, 12, COLORS.purple, 14);
      applyBrutalBorder(submitBtn, COLORS.dark, 2);
      submitBtn.layoutSizingHorizontal = 'FILL';
      submitBtn.primaryAxisAlignItems = 'CENTER';
      submitBtn.appendChild(makeText('🚀 Kirim Request Lagu', 13, 'Bold', COLORS.white));
      formCard.appendChild(submitBtn);

      phone2.appendChild(formCard);

      figma.currentPage.appendChild(phone2);
      currentX += phone2.width + 40;

      // SCREEN 3: Status Request Saya
      const phone3 = makeAutoLayout('📱 Mobile - 3. Status Request Saya', 'VERTICAL', 14, 16, COLORS.grayBg, 36);
      phone3.resize(390, 844);
      applyBrutalBorder(phone3, COLORS.dark, 3);
      phone3.x = currentX;
      phone3.y = currentY;

      phone3.appendChild(makeText('🙋‍♂️ Riwayat Pengajuan Saya', 16, 'Bold', COLORS.dark));

      const myCard1 = makeAutoLayout('Status Card Approved', 'VERTICAL', 6, 12, COLORS.mintSoft, 16);
      applyBrutalBorder(myCard1, COLORS.dark, 1.5);
      myCard1.layoutSizingHorizontal = 'FILL';
      myCard1.appendChild(makeText('Jiwa yang Bersedih — Ghea Indrawari', 12, 'Bold', COLORS.dark));
      myCard1.appendChild(makeText('Status: Sedang Diputar (ON AIR)', 11, 'Bold', COLORS.mint));
      phone3.appendChild(myCard1);

      const myCard2 = makeAutoLayout('Status Card Rejected', 'VERTICAL', 6, 12, COLORS.roseSoft, 16);
      applyBrutalBorder(myCard2, COLORS.dark, 1.5);
      myCard2.layoutSizingHorizontal = 'FILL';
      myCard2.appendChild(makeText('Lagu Death Metal Kasar', 12, 'Bold', COLORS.dark));
      myCard2.appendChild(makeText('Status: Ditolak OSIS', 11, 'Bold', COLORS.rose));
      const reasonBox = makeAutoLayout('Reason', 'HORIZONTAL', 0, 6, COLORS.white, 8);
      reasonBox.layoutSizingHorizontal = 'FILL';
      reasonBox.appendChild(makeText('Alasan: "Lirik kurang pantas untuk sekolah."', 10, 'Medium', COLORS.dark));
      myCard2.appendChild(reasonBox);
      phone3.appendChild(myCard2);

      figma.currentPage.appendChild(phone3);
      currentX += phone3.width + 40;

      // SCREEN 4: Profil & Kritik Saran
      const phone4 = makeAutoLayout('📱 Mobile - 4. Profil & Kritik Saran', 'VERTICAL', 14, 16, COLORS.grayBg, 36);
      phone4.resize(390, 844);
      applyBrutalBorder(phone4, COLORS.dark, 3);
      phone4.x = currentX;
      phone4.y = currentY;

      phone4.appendChild(makeText('💬 Kritik & Saran Radio OSIS', 16, 'Bold', COLORS.dark));

      const profCard = makeAutoLayout('Profile Box', 'VERTICAL', 8, 12, COLORS.white, 16);
      applyBrutalBorder(profCard, COLORS.dark, 1.5);
      profCard.layoutSizingHorizontal = 'FILL';
      profCard.appendChild(makeText('Identitas Siswa: Alif (NIS: 14144001)', 12, 'Bold', COLORS.dark));
      profCard.appendChild(makeText('Kelas: XII RPL 1', 11, 'Regular', COLORS.gray500));
      phone4.appendChild(profCard);

      const feedbackForm = makeAutoLayout('Feedback Form', 'VERTICAL', 8, 12, COLORS.white, 16);
      applyBrutalBorder(feedbackForm, COLORS.dark, 1.5);
      feedbackForm.layoutSizingHorizontal = 'FILL';
      feedbackForm.appendChild(makeText('Kategori: 💡 Saran Acara', 11, 'Bold', COLORS.purple));
      const fbInput = makeAutoLayout('Input', 'HORIZONTAL', 0, 8, COLORS.grayBg, 10);
      fbInput.layoutSizingHorizontal = 'FILL';
      applyBrutalBorder(fbInput, COLORS.dark, 1);
      fbInput.appendChild(makeText('Bisa tambahkan sesi request lagu akustik min?', 11, 'Regular', COLORS.dark));
      feedbackForm.appendChild(fbInput);

      const sendFbBtn = makeAutoLayout('Send Button', 'HORIZONTAL', 0, 10, COLORS.pink, 12);
      applyBrutalBorder(sendFbBtn, COLORS.dark, 1.5);
      sendFbBtn.layoutSizingHorizontal = 'FILL';
      sendFbBtn.primaryAxisAlignItems = 'CENTER';
      sendFbBtn.appendChild(makeText('Kirim Masukan ke OSIS', 12, 'Bold', COLORS.white));
      feedbackForm.appendChild(sendFbBtn);
      phone4.appendChild(feedbackForm);

      figma.currentPage.appendChild(phone4);
      currentX += phone4.width + 60;
    }

    // ──────────────────────────────────────────
    // C. DESKTOP DJ STUDIO ADMIN (1440 x 900 px)
    // ──────────────────────────────────────────
    if (msg.desktop) {
      const desk1 = makeAutoLayout('💻 Desktop - 1. DJ Studio & Pemutar Siaran', 'VERTICAL', 16, 24, COLORS.grayBg, 24);
      desk1.resize(1440, 900);
      applyBrutalBorder(desk1, COLORS.dark, 3);
      desk1.x = currentX;
      desk1.y = currentY;

      // Top Desktop Bar
      const deskTopBar = makeAutoLayout('Desk Top Bar', 'HORIZONTAL', 16, 12, COLORS.white, 16);
      applyBrutalBorder(deskTopBar, COLORS.dark, 2);
      deskTopBar.layoutSizingHorizontal = 'FILL';
      deskTopBar.primaryAxisAlignItems = 'SPACE_BETWEEN';
      deskTopBar.appendChild(makeText('🎛️ SELADA FM — DJ BROADCAST STUDIO (OPERATOR OSIS)', 15, 'Bold', COLORS.purple));
      deskTopBar.appendChild(makeText('● SIARAN AKTIF: 11:45 - 12:30 (JAM ISTIRAHAT)', 12, 'Bold', COLORS.mint));
      desk1.appendChild(deskTopBar);

      // Desktop Main Columns
      const mainCols = makeAutoLayout('Main Columns', 'HORIZONTAL', 20);
      mainCols.layoutSizingHorizontal = 'FILL';

      // Left Column (Player 800px)
      const leftCol = makeAutoLayout('Left Player Column', 'VERTICAL', 14);
      leftCol.resize(840, 760);

      const playerScreen = makeAutoLayout('Video Player Mockup', 'VERTICAL', 12, 16, COLORS.dark, 20);
      applyBrutalBorder(playerScreen, COLORS.dark, 2);
      playerScreen.layoutSizingHorizontal = 'FILL';
      playerScreen.resize(840, 380);
      playerScreen.primaryAxisAlignItems = 'CENTER';
      playerScreen.counterAxisAlignItems = 'CENTER';
      playerScreen.appendChild(makeText('▶ YOUTUBE EMBED PLAYER', 18, 'Bold', COLORS.white));
      playerScreen.appendChild(makeText('Ghea Indrawari — Jiwa yang Bersedih (02:14 / 03:42)', 13, 'Regular', COLORS.lavender));
      leftCol.appendChild(playerScreen);

      // Controls Bar
      const controlBar = makeAutoLayout('Controls Bar', 'HORIZONTAL', 12, 12, COLORS.white, 16);
      applyBrutalBorder(controlBar, COLORS.dark, 2);
      controlBar.layoutSizingHorizontal = 'FILL';

      const playBtn = makeAutoLayout('Play/Pause', 'HORIZONTAL', 0, 10, COLORS.yellow, 12);
      applyBrutalBorder(playBtn);
      playBtn.appendChild(makeText('⏸ Pause Siaran', 12, 'Bold', COLORS.dark));
      controlBar.appendChild(playBtn);

      const skipBtn = makeAutoLayout('Skip', 'HORIZONTAL', 0, 10, COLORS.pink, 12);
      applyBrutalBorder(skipBtn);
      skipBtn.appendChild(makeText('⏭ Putar Berikutnya', 12, 'Bold', COLORS.white));
      controlBar.appendChild(skipBtn);

      const djBox = makeAutoLayout('DJ Shoutout', 'VERTICAL', 2, 8, COLORS.lavender, 10);
      djBox.layoutSizingHorizontal = 'FILL';
      djBox.appendChild(makeText('📢 Teks Bacaan DJ:', 10, 'Bold', COLORS.purple));
      djBox.appendChild(makeText('"Semangat try out USK buat XII RPL 1! — Dari Alif"', 11, 'Semi Bold', COLORS.dark));
      controlBar.appendChild(djBox);

      leftCol.appendChild(controlBar);
      mainCols.appendChild(leftCol);

      // Right Column (Queue & Moderation 500px)
      const rightCol = makeAutoLayout('Right Queue Column', 'VERTICAL', 14);
      rightCol.resize(500, 760);

      const qBox = makeAutoLayout('Queue Container', 'VERTICAL', 10, 14, COLORS.white, 20);
      applyBrutalBorder(qBox, COLORS.dark, 2);
      qBox.layoutSizingHorizontal = 'FILL';
      qBox.appendChild(makeText('📋 Antrian Lagu Siap Mengudara', 14, 'Bold', COLORS.dark));

      const qSongs = [
        { title: '1. Sal Priadi — Dari planet lain', req: 'Nabila (XI MIPA 2)', vote: '12 Votes' },
        { title: '2. Coldplay — Viva La Vida', req: 'Budi (X TKJ 3)', vote: '8 Votes' },
        { title: '3. Tulus — Tujuh Belas', req: 'Cindy (XII AK)', vote: '3 Votes' },
      ];

      for (const qs of qSongs) {
        const qi = makeAutoLayout('Item', 'HORIZONTAL', 0, 8, COLORS.grayBg, 12);
        applyBrutalBorder(qi, COLORS.dark, 1);
        qi.layoutSizingHorizontal = 'FILL';
        qi.primaryAxisAlignItems = 'SPACE_BETWEEN';
        qi.appendChild(makeText(qs.title + ' (' + qs.req + ')', 11, 'Bold', COLORS.dark));
        qi.appendChild(makeText(qs.vote, 10, 'Bold', COLORS.purple));
        qBox.appendChild(qi);
      }
      rightCol.appendChild(qBox);

      // Moderation Box
      const modBox = makeAutoLayout('Moderation Container', 'VERTICAL', 10, 14, COLORS.white, 20);
      applyBrutalBorder(modBox, COLORS.dark, 2);
      modBox.layoutSizingHorizontal = 'FILL';
      modBox.appendChild(makeText('⚖️ Moderasi Request Baru (1 Masuk)', 14, 'Bold', COLORS.dark));
      const modItem = makeAutoLayout('Mod Item', 'HORIZONTAL', 8, 8, COLORS.cream, 12);
      applyBrutalBorder(modItem, COLORS.dark, 1);
      modItem.layoutSizingHorizontal = 'FILL';
      modItem.primaryAxisAlignItems = 'SPACE_BETWEEN';
      modItem.appendChild(makeText('LANY — Malibu Nights (Reza - X RPL)', 11, 'Bold', COLORS.dark));
      const modBtns = makeAutoLayout('Btns', 'HORIZONTAL', 4);
      const appBtn = makeAutoLayout('App', 'HORIZONTAL', 0, 4, COLORS.mint, 6);
      appBtn.appendChild(makeText('✓ Setujui', 10, 'Bold', COLORS.white));
      modBtns.appendChild(appBtn);
      const rejBtn = makeAutoLayout('Rej', 'HORIZONTAL', 0, 4, COLORS.rose, 6);
      rejBtn.appendChild(makeText('✗ Tolak', 10, 'Bold', COLORS.white));
      modBtns.appendChild(rejBtn);
      modItem.appendChild(modBtns);
      modBox.appendChild(modItem);
      rightCol.appendChild(modBox);

      mainCols.appendChild(rightCol);
      desk1.appendChild(mainCols);

      figma.currentPage.appendChild(desk1);
    }

    figma.ui.postMessage({ type: 'done' });
  }
};
