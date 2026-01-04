<script>
document.addEventListener('DOMContentLoaded', () => {
    // Register ScrollTrigger jika Anda menggunakan CDN ScrollTrigger
    gsap.registerPlugin(ScrollTrigger);

    const tl = gsap.timeline({ defaults: { ease: "power4.out" } });

    // 1. Hero Section Animation (Load awal)
    tl.from(".display-4", { 
        y: 60, 
        opacity: 0, 
        duration: 1.2,
        skewY: 7 
    })
    .from(".lead", { 
        y: 30, 
        opacity: 0, 
        duration: 1 
    }, "-=0.8")
    .from(".main-tool-card", { 
        y: 100, 
        opacity: 0, 
        scale: 0.9, 
        duration: 1.5,
        boxShadow: "0 0 0 rgba(0,0,0,0)" 
    }, "-=0.8")
    .from(".nav-custom .nav-item", { 
        y: 20, 
        opacity: 0, 
        stagger: 0.1, 
        duration: 0.8 
    }, "-=1");

    // 2. Stats Number Counter Animation
    const statsItems = document.querySelectorAll('.col-4 h4');

    statsItems.forEach((stat) => {
        const fullText = stat.innerText;

        // Filter: Jangan jalankan animasi jika teks mengandung "End-to-End"
        if (fullText.toLowerCase().includes('end-to-end')) {
            return; 
        }

        const numberVal = parseFloat(fullText.replace(/[^0-9.]/g, ''));
        const suffix = fullText.replace(/[0-9.]/g, '');

        gsap.from(stat, {
            scrollTrigger: {
                trigger: stat,
                start: "top 90%",
            },
            innerText: 0,
            duration: 2,
            snap: { innerText: numberVal % 1 === 0 ? 1 : 0.1 },
            onUpdate: function() {
                // Memastikan suffix (+, %, dll) tidak hilang saat counting
                const currentVal = parseFloat(stat.innerText).toFixed(numberVal % 1 === 0 ? 0 : 1);
                stat.innerText = currentVal + suffix;
            }
        });
    });

    // 3. "Kenapa Profesional Memilih Kami?" Section
    gsap.from(".col-md-6 .position-relative img", {
        scrollTrigger: {
            trigger: ".col-md-6 .position-relative img",
            start: "top 80%",
        },
        x: -100,
        opacity: 0,
        duration: 1.2,
        ease: "back.out(1.7)"
    });

    gsap.from(".col-md-6 .d-flex", {
        scrollTrigger: {
            trigger: ".col-md-6 .d-flex",
            start: "top 85%",
        },
        x: 50,
        opacity: 0,
        stagger: 0.3,
        duration: 1,
        ease: "power2.out"
    });

    // 4. Floating Glow Effect (Background Hero)
    gsap.to(".translate-middle", {
        scale: 1.2,
        opacity: 0.15,
        duration: 3,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut"
    });

    // 5. CTA Register Button Pulse (Gueat only)
    if(document.querySelector('.bg-primary.rounded-4.p-4')) {
        gsap.from(".bg-primary.rounded-4.p-4", {
            scrollTrigger: {
                trigger: ".bg-primary.rounded-4.p-4",
                start: "top 90%",
            },
            y: 40,
            opacity: 0,
            duration: 1
        });
    }

    // Fungsi pembantu agar tidak menulis kode berulang
    const animateElement = (trigger, target, fromVars, toVars) => {
        gsap.fromTo(target, fromVars, {
            ...toVars,
            scrollTrigger: {
                trigger: trigger,
                start: "top 85%", // Animasi mulai saat bagian atas elemen di 85% layar
                toggleActions: "play none none none",
                // markers: true // HAPUS TANDA KOMENTAR (//) ini untuk melihat garis pandu di layar
            }
        });
    };

    // --- ANIMASI SECTION FITUR ---
    // Target: Header Fitur
    animateElement("#fitur", "#fitur .text-center", 
        { opacity: 0, y: 30 }, 
        { opacity: 1, y: 0, duration: 0.8 }
    );

    // Target: Kartu-kartu Fitur
    gsap.fromTo("#fitur .glass-card", 
        { opacity: 0, y: 50 }, 
        {
            opacity: 1, 
            y: 0, 
            duration: 0.8, 
            stagger: 0.2, 
            ease: "back.out(1.7)",
            scrollTrigger: {
                trigger: "#fitur .row",
                start: "top 80%",
                toggleActions: "play none none none"
            }
        }
    );

    // --- ANIMASI SECTION KONTAK ---
    // Target: Card Utama Kontak
    animateElement("#kontak", "#kontak .main-tool-card", 
        { opacity: 0, scale: 0.9 }, 
        { opacity: 1, scale: 1, duration: 1 }
    );

    // Target: Input & Link Kontak
    gsap.fromTo("#kontak .contact-link-item, #kontak .form-control, #kontak .btn", 
        { opacity: 0, x: -20 }, 
        {
            opacity: 1, 
            x: 0, 
            duration: 0.6, 
            stagger: 0.1,
            scrollTrigger: {
                trigger: "#kontak .main-tool-card",
                start: "top 70%",
                toggleActions: "play none none none"
            }
        }
    );

    const ctaContainer = document.querySelector(".cta-banner-container");
    const ctaBtn = ctaContainer.querySelector(".cta-btn-pulse");
    
    // Set visibility kembali visible sebelum animasi mulai agar GSAP bisa bekerja
    gsap.set(ctaContainer, { visibility: "visible" });

    // 1. Timeline Utama untuk Entry Animation
    const ctaTl = gsap.timeline({
        scrollTrigger: {
            trigger: ctaContainer,
            start: "top 85%", // Mulai sedikit lebih awal
            toggleActions: "play none none none"
        }
    });

    ctaTl
    // Tahap A: Banner Utama Muncul (Elastic Pop + 3D Tilt)
    .fromTo(ctaContainer, 
        { 
            opacity: 0, 
            scale: 0.8, 
            rotationX: 15, // Efek miring 3D sedikit
            transformPerspective: 1000
        }, 
        { 
            opacity: 1, 
            scale: 1, 
            rotationX: 0,
            duration: 1.2, 
            ease: "elastic.out(1, 0.75)", // Efek membal
            clearProps: "transformPerspective, rotationX" // Bersihkan properti 3D setelah selesai agar teks tajam
        }
    )
    // Tahap B: Teks dan Tombol Muncul Bergantian (Stagger)
    .fromTo([ctaContainer.querySelectorAll("h5, p"), ctaBtn], 
        { 
            y: 20, 
            opacity: 0 
        }, 
        { 
            y: 0, 
            opacity: 1, 
            stagger: 0.15, // Muncul satu per satu dengan jeda
            duration: 0.6,
            ease: "power2.out"
        }, 
        "-=0.8" // Mulai animasi ini 0.8 detik sebelum animasi banner selesai (overlap)
    );


    // 2. Animasi Denyut Berulang (Continuous Pulse) untuk Tombol
    // Ini berjalan terpisah dari timeline utama agar terus berulang
    gsap.to(ctaBtn, {
        scale: 1.05, // Membesar sedikit
        boxShadow: "0 0 20px rgba(255, 255, 255, 0.7)", // Efek glow putih
        duration: 1,
        repeat: -1, // Ulangi terus menerus
        yoyo: true, // Bolak-balik (membesar-mengecil)
        ease: "sine.inOut",
        delay: 1.5 // Tunggu sebentar sampai animasi entry selesai baru mulai denyut
    });
});
</script>
