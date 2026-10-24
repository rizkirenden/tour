/**
 * public/js/keluarga-edit.js
 * Logika JS untuk halaman edit keluarga.
 * TIDAK memakai template literal (backtick) agar tidak error parsing di browser lama.
 */
(function () {
    "use strict";

    // ===== CONFIG =====
    var CFG = {};
    try {
        var cfgEl = document.getElementById("keluarga-config");
        if (cfgEl) CFG = JSON.parse(cfgEl.textContent);
    } catch (e) {
        console.error("Config parse error:", e);
    }

    var KDATA = {
        hotelOptions: CFG.hotelOptions || [],
        hubunganOptions: CFG.hubunganOptions || [],
        kotaAsals: CFG.kotaAsals || [],
        excludeJamaahIds: CFG.excludeJamaahIds || [],
        existingUpgradeKeluarga: CFG.existingUpgradeKeluarga || [],
        existingUpgradeJamaah: CFG.existingUpgradeJamaah || {},
        routes: CFG.routes || {},
    };

    var OLD_ID_HARGA = parseInt(CFG.oldIdHarga) || 0;
    var OLD_ID_KEBERANGKATAN = parseInt(CFG.oldIdKeberangkatan) || 0;
    var jamaahIndex = parseInt(CFG.existingJamaahCount) || 1;

    var globalKeberangkatanList = [];
    var globalHargaBulananList = [];

    // ===== FORMAT RUPIAH =====
    window.formatRupiah = function (el) {
        var raw = el.value.replace(/[^,\d]/g, "");
        if (raw === "") {
            el.value = "";
            return;
        }
        raw = raw.replace(/^0+(?=\d)/, "");
        el.value = raw.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };
    window.handleZeroFocus = function (el) {
        if (el.value === "0" || el.value === "0.000" || el.value === "")
            el.value = "";
    };
    window.handleZeroBlur = function (el) {
        if (el.value === "" || el.value === "0") el.value = "0";
        window.formatRupiah(el);
    };
    window.formatRupiahKamar = function (input) {
        var v = input.value
            .replace(/[^,\d]/g, "")
            .replace(/^Rp\s*/, "")
            .replace(/\./g, "");
        if (v === "" || isNaN(v)) v = "0";
        input.value = "Rp " + parseInt(v).toLocaleString("id-ID");
    };

    // ===== FEE AGENT PER JAMAAH =====
    window.updateFeeAgentPerJamaah = function () {
        var inp = document.getElementById("fee_agent");
        if (!inp) return;
        var container = document.getElementById("feeAgentPerJamaahInfo");
        var elTotal = document.getElementById("infoTotalFeeAgent");
        var elJumlah = document.getElementById("infoJumlahJamaahFee");
        var elPerOrang = document.getElementById("infoFeeAgentPerOrang");
        var total = parseInt(inp.value.replace(/\./g, "")) || 0;
        var jml = document.querySelectorAll(".jamaah-row").length;
        var per = jml > 0 ? Math.round(total / jml) : 0;
        if (total > 0 && jml > 0) {
            container.classList.remove("hidden");
            elTotal.textContent = "Rp " + total.toLocaleString("id-ID");
            elJumlah.textContent = jml + " orang";
            elPerOrang.textContent = "Rp " + per.toLocaleString("id-ID");
        } else {
            container.classList.add("hidden");
        }
    };

    // ===== PEMBUATAN PASSPORT =====
    window.setPembuatanPassportKeluarga = function (index, value) {
        var hidden = document.getElementById("is_pembuatan_passport-" + index);
        var btnYa = document.getElementById("btnYa-" + index);
        var btnTidak = document.getElementById("btnTidak-" + index);
        var container = document.getElementById("feePetugasContainer-" + index);
        var petugasInput = document.getElementById(
            "pendampingan_fee_petugas-" + index,
        );
        if (!hidden || !btnYa || !btnTidak) return;
        hidden.value = value;
        if (value === 1) {
            btnYa.className =
                "px-6 py-2 text-sm font-medium bg-green-500 text-white transition-colors";
            btnTidak.className =
                "px-6 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors border-l border-gray-300";
            if (container) container.classList.remove("hidden");
        } else {
            btnYa.className =
                "px-6 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors";
            btnTidak.className =
                "px-6 py-2 text-sm font-medium bg-red-500 text-white transition-colors border-l border-gray-300";
            if (container) container.classList.add("hidden");
            if (petugasInput) petugasInput.value = "0";
        }
        window.updateTotalPendampingan(index);
    };

    window.updateTotalPendampingan = function (index) {
        var feeInput = document.querySelector(
            'input[name="jamaahs[' + index + '][pendampingan_fee]"]',
        );
        var petugasInput = document.getElementById(
            "pendampingan_fee_petugas-" + index,
        );
        var totalSpan = document.getElementById("total-pendampingan-" + index);
        var hidden = document.getElementById("is_pembuatan_passport-" + index);
        if (feeInput && totalSpan) {
            var fee = parseInt(feeInput.value.replace(/\./g, "")) || 0;
            var petugas = 0;
            if (hidden && hidden.value === "1" && petugasInput) {
                petugas = parseInt(petugasInput.value.replace(/\./g, "")) || 0;
            }
            totalSpan.textContent =
                "Rp " + (fee + petugas).toLocaleString("id-ID");
        }
    };

    // ===== DISKON =====
    window.hitungDiskonKelompok = function () {
        var sel = document.getElementById("id_diskon");
        if (!sel) return;
        var container = document.getElementById("infoDiskonContainer");
        var elTotal = document.getElementById("infoTotalDiskon");
        var elJumlah = document.getElementById("infoJumlahJamaah");
        var elPerOrang = document.getElementById("infoDiskonPerOrang");
        var opt = sel.options[sel.selectedIndex];
        var total = parseInt(opt.dataset.nilai || 0);
        var jml = document.querySelectorAll(".jamaah-row").length;
        var per = jml > 0 ? Math.round(total / jml) : 0;
        if (total > 0 && jml > 0) {
            container.classList.remove("hidden");
            elTotal.textContent = "Rp " + total.toLocaleString("id-ID");
            elJumlah.textContent = jml + " orang";
            elPerOrang.textContent = "Rp " + per.toLocaleString("id-ID");
        } else {
            container.classList.add("hidden");
        }
    };

    // ===== VALIDASI =====
    function validateJamaahForm() {
        var labels = {
            nama_lengkap: "Nama Lengkap",
            nik: "NIK",
            nama_ayah: "Nama Ayah",
            pekerjaan: "Pekerjaan",
            jenis_kelamin: "Jenis Kelamin",
            status_kawin: "Status Kawin",
            telepon: "Telepon",
            wa: "WhatsApp",
            tempat_lahir: "Tempat Lahir",
        };
        var missing = [];
        var firstInvalid = null;
        document.querySelectorAll(".jamaah-row").forEach(function (row, idx) {
            row.querySelectorAll(".wajib-jamaah").forEach(function (f) {
                var v = (f.value || "").trim();
                if (!v) {
                    f.classList.add("border-red-500", "ring-2", "ring-red-300");
                    f.classList.remove("border-gray-200");
                    var key = f.name.replace(/jamaahs\[\d+\]\[|\]/g, "");
                    missing.push(
                        "Jamaah #" + (idx + 1) + " - " + (labels[key] || key),
                    );
                    if (!firstInvalid) firstInvalid = f;
                } else {
                    f.classList.remove(
                        "border-red-500",
                        "ring-2",
                        "ring-red-300",
                    );
                    f.classList.add("border-gray-200");
                }
            });
        });
        var errBox = document.getElementById("jamaahError");
        var errText = document.getElementById("jamaahErrorText");
        if (missing.length > 0) {
            errText.textContent =
                "Data jamaah belum lengkap: " +
                missing.slice(0, 5).join(", ") +
                (missing.length > 5
                    ? " dan " + (missing.length - 5) + " lainnya."
                    : ".");
            errBox.classList.remove("hidden");
            if (firstInvalid) {
                firstInvalid.scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                });
                firstInvalid.focus();
            }
            return false;
        }
        errBox.classList.add("hidden");
        return true;
    }

    // ===== KEBERANGKATAN =====
    function loadKeberangkatan() {
        var produk = document.getElementById("produk_paket");
        var tahunSelect = document.getElementById("filter-tahun");
        var step1 = document.getElementById("step-1-container");
        var step2 = document.getElementById("step-2-container");
        var placeholder = document.getElementById("placeholder-belum-pilih");
        var container = document.getElementById("keberangkatan-container");

        tahunSelect.innerHTML = '<option value="">-- Pilih Tahun --</option>';
        step1.classList.add("hidden");
        step2.classList.add("hidden");
        container.innerHTML = "";
        resetHiddenInputs();

        if (!produk.value) {
            placeholder.classList.remove("hidden");
            return;
        }
        placeholder.classList.add("hidden");
        step1.classList.remove("hidden");
        tahunSelect.innerHTML = '<option value="">-- Memuat... --</option>';

        var url =
            KDATA.routes.keberangkatanByProduk +
            "?produk_paket=" +
            encodeURIComponent(produk.value);
        if (KDATA.excludeJamaahIds.length > 0) {
            url += "&exclude_jamaah_ids=" + KDATA.excludeJamaahIds.join(",");
        }

        fetch(url)
            .then(function (r) {
                return r.json();
            })
            .then(function (data) {
                if (
                    !data.success ||
                    !data.keberangkatan ||
                    data.keberangkatan.length === 0
                ) {
                    tahunSelect.innerHTML =
                        '<option value="">-- Tidak ada data --</option>';
                    return;
                }
                globalKeberangkatanList = data.keberangkatan;
                globalHargaBulananList = data.harga_bulanan || [];

                var tahunSet = new Set();
                globalKeberangkatanList.forEach(function (k) {
                    tahunSet.add(k.tahun);
                });
                var tahunList = Array.from(tahunSet).sort(function (a, b) {
                    return a - b;
                });
                tahunSelect.innerHTML =
                    '<option value="">-- Pilih Tahun --</option>';
                tahunList.forEach(function (t) {
                    var o = document.createElement("option");
                    o.value = t;
                    o.textContent = t;
                    tahunSelect.appendChild(o);
                });

                var selectedTahun = document.getElementById(
                    "tahun_keberangkatan_hidden",
                ).value;
                if (
                    selectedTahun &&
                    tahunList.indexOf(parseInt(selectedTahun)) !== -1
                ) {
                    tahunSelect.value = selectedTahun;
                    renderKeberangkatanByTahun(selectedTahun);
                } else if (tahunList.length > 0) {
                    tahunSelect.value = tahunList[0];
                    renderKeberangkatanByTahun(tahunList[0]);
                }
            })
            .catch(function (err) {
                console.error("Error:", err);
                tahunSelect.innerHTML =
                    '<option value="">-- Gagal memuat --</option>';
            });
    }

    function renderKeberangkatanByTahun(tahun) {
        var step2 = document.getElementById("step-2-container");
        var container = document.getElementById("keberangkatan-container");
        if (!tahun) {
            step2.classList.add("hidden");
            resetHiddenInputs();
            return;
        }
        step2.classList.remove("hidden");
        var filtered = globalKeberangkatanList.filter(function (k) {
            return parseInt(k.tahun) === parseInt(tahun);
        });
        if (filtered.length === 0) {
            container.innerHTML =
                '<div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200 text-center">' +
                '<p class="text-sm text-yellow-600"><i class="fas fa-exclamation-triangle mr-1"></i>Tidak ada keberangkatan di tahun ' +
                tahun +
                "</p></div>";
            return;
        }
        var selKebId = document.getElementById(
            "id_produk_keberangkatan_hidden",
        ).value;
        var selectedKeb = null;
        if (selKebId) {
            selectedKeb =
                filtered.find(function (k) {
                    return parseInt(k.id) === parseInt(selKebId);
                }) || null;
        }
        var opt = '<option value="">-- Pilih Keberangkatan --</option>';
        filtered.forEach(function (k) {
            var isCur =
                selectedKeb && parseInt(selectedKeb.id) === parseInt(k.id);
            var isFull = k.kuota_default_is_full;
            var label =
                k.keberangkatan_formatted +
                " \u2014 " +
                k.harga_default_formatted;
            if (isFull && !isCur) label += " (KUOTA PENUH)";
            var dis = isFull && !isCur ? "disabled" : "";
            var sel = isCur ? "selected" : "";
            opt +=
                '<option value="' +
                k.id +
                '" ' +
                sel +
                " " +
                dis +
                ">" +
                label +
                "</option>";
        });
        container.innerHTML =
            '<select id="keberangkatan-select" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">' +
            opt +
            '</select><div id="keberangkatan-harga-wrapper" class="mt-3"></div>';
        document
            .getElementById("keberangkatan-select")
            .addEventListener("change", handleKeberangkatanSelectChange);
        if (selectedKeb) {
            handleKeberangkatanSelectChange({
                target: { value: selectedKeb.id },
            });
        }
    }

    function handleKeberangkatanSelectChange(e) {
        var id = e.target.value;
        var wrapper = document.getElementById("keberangkatan-harga-wrapper");
        if (!id) {
            wrapper.innerHTML = "";
            document.getElementById("id_produk_keberangkatan_hidden").value =
                "";
            document.getElementById("bulan_keberangkatan_hidden").value = "";
            document.getElementById("tahun_keberangkatan_hidden").value = "";
            document.getElementById("id_produk_harga_bulanan_hidden").value =
                "";
            document.getElementById("hargaInfo").classList.add("hidden");
            return;
        }
        var k = globalKeberangkatanList.find(function (x) {
            return parseInt(x.id) === parseInt(id);
        });
        if (!k) {
            wrapper.innerHTML = "";
            return;
        }
        var curHargaId =
            document.getElementById("id_produk_harga_bulanan_hidden").value ||
            "";
        document.getElementById("id_produk_keberangkatan_hidden").value = k.id;
        document.getElementById("bulan_keberangkatan_hidden").value = k.bulan;
        document.getElementById("tahun_keberangkatan_hidden").value = k.tahun;
        if (parseInt(id) !== OLD_ID_KEBERANGKATAN) {
            curHargaId = "";
            document.getElementById("id_produk_harga_bulanan_hidden").value =
                "";
            document.getElementById("bulan_harga_hidden").value = k.bulan;
            document.getElementById("tahun_harga_hidden").value = k.tahun;
        }
        var curHarga = parseInt(k.harga_default || 0);
        var curBulanHarga = k.bulan;
        var curTahunHarga = k.tahun;
        if (curHargaId) {
            var match = globalHargaBulananList.find(function (h) {
                return parseInt(h.id) === parseInt(curHargaId);
            });
            if (match) {
                curHarga = parseInt(match.harga);
                curBulanHarga = match.bulan;
                curTahunHarga = match.tahun;
            }
        }
        var hargaOptions =
            '<option value="">-- Sama dengan keberangkatan (' +
            k.bulan_formatted +
            ") --</option>";
        globalHargaBulananList.forEach(function (h) {
            var isCur = parseInt(h.id) === parseInt(curHargaId);
            var sel = isCur ? "selected" : "";
            var full = h.is_full;
            var dis = full && !isCur ? "disabled" : "";
            var kuotaLabel = h.is_full
                ? "PENUH (" + h.kuota + ")"
                : h.kuota_formatted;
            hargaOptions +=
                '<option value="' +
                h.id +
                '" data-bulan="' +
                h.bulan +
                '" data-tahun="' +
                h.tahun +
                '" data-harga="' +
                h.harga +
                '" data-kuota="' +
                (h.kuota || 0) +
                '" data-is-full="' +
                (full ? "1" : "0") +
                '" ' +
                sel +
                " " +
                dis +
                ">" +
                h.bulan_formatted +
                " - " +
                h.harga_formatted +
                " (Kuota: " +
                kuotaLabel +
                ")" +
                (full ? " \u26D4" : "") +
                "</option>";
        });
        wrapper.innerHTML =
            '<div class="keberangkatan-harga-section mt-3 bg-gray-50 rounded-xl p-3 border border-gray-200"><label class="block text-xs font-medium text-gray-600 mb-1.5"><i class="fas fa-tag text-yellow-500 mr-1"></i>Harga diambil dari bulan/tahun:</label><select class="harga-bulanan-select w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">' +
            hargaOptions +
            '</select><div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-200"><p class="text-xs text-gray-500">Harga dipilih:</p><p class="text-sm font-bold text-green-600">Rp ' +
            curHarga.toLocaleString("id-ID") +
            "</p></div></div>";
        var hargaSel = wrapper.querySelector(".harga-bulanan-select");
        if (hargaSel)
            hargaSel.addEventListener("change", handleHargaBulananChange);
        updateHargaInfo(curBulanHarga, curTahunHarga, curHarga);
    }

    function handleHargaBulananChange(e) {
        var sel = e.target;
        var wrapper = sel.closest(".keberangkatan-harga-section");
        var opt = sel.options[sel.selectedIndex];
        var hargaId = sel.value;
        var kebSelect = document.getElementById("keberangkatan-select");
        var kebId = kebSelect ? kebSelect.value : null;
        var k = globalKeberangkatanList.find(function (x) {
            return parseInt(x.id) === parseInt(kebId);
        });
        if (!k) return;
        var bulan, tahun, harga, kuota, isFull;
        if (hargaId) {
            bulan = opt.dataset.bulan;
            tahun = opt.dataset.tahun;
            harga = opt.dataset.harga;
            kuota = parseInt(opt.dataset.kuota) || 0;
            isFull = opt.dataset.isFull === "1";
        } else {
            bulan = k.bulan;
            tahun = k.tahun;
            harga = k.harga_default;
            kuota = parseInt(k.kuota_default) || 0;
            isFull = k.kuota_default_is_full;
        }
        var isOld = parseInt(hargaId || 0) === OLD_ID_HARGA && OLD_ID_HARGA > 0;
        if (isFull && !isOld) {
            alert("Kuota bulan/tahun ini sudah PENUH! Silakan pilih lain.");
            sel.value = "";
            return;
        }
        document.getElementById("id_produk_harga_bulanan_hidden").value =
            hargaId || "";
        document.getElementById("bulan_harga_hidden").value = bulan;
        document.getElementById("tahun_harga_hidden").value = tahun;
        var disp = wrapper.querySelector(".harga-display-" + k.id);
        if (disp)
            disp.textContent =
                "Rp " + parseInt(harga || 0).toLocaleString("id-ID");
        updateHargaInfo(bulan, tahun, harga);
    }

    function updateHargaInfo(bulan, tahun, harga) {
        document.getElementById("hargaBulanTahun").textContent =
            getBulanName(bulan) + " " + tahun;
        document.getElementById("hargaProdukDisplay").textContent =
            "Rp " + parseInt(harga || 0).toLocaleString("id-ID");
        document.getElementById("hargaInfo").classList.remove("hidden");
    }

    function resetHiddenInputs() {
        [
            "bulan_keberangkatan_hidden",
            "tahun_keberangkatan_hidden",
            "id_produk_keberangkatan_hidden",
            "bulan_harga_hidden",
            "tahun_harga_hidden",
            "id_produk_harga_bulanan_hidden",
        ].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.value = "";
        });
        document.getElementById("hargaInfo").classList.add("hidden");
    }

    function getBulanName(b) {
        var n = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];
        return n[parseInt(b) - 1] || b;
    }

    // ===== UPGRADE KELUARGA =====
    window.toggleUpgradeKeluarga = function () {
        var el = document.getElementById("upgradeKeluargaContainer");
        if (el) el.classList.toggle("hidden");
    };
    window.toggleHotelDetailKeluarga = function (hotelId) {
        var cb = document.querySelector(
            '.upgrade-hotel-checkbox[data-scope="keluarga"][data-hotel-id="' +
                hotelId +
                '"]',
        );
        var c = document.getElementById(
            "hotelDetailContainer-keluarga-" + hotelId,
        );
        if (!cb || !c) return;
        if (cb.checked) {
            c.classList.remove("hidden");
            loadKamarsUpgrade("keluarga", hotelId, c);
        } else {
            c.classList.add("hidden");
            c.innerHTML =
                '<div class="text-xs text-gray-400 text-center py-2">Pilih hotel untuk memuat kamar</div>';
            removeUpgradeHiddenInputs("keluarga", hotelId);
            updateTotalUpgradeKeluarga();
        }
    };
    function updateTotalUpgradeKeluarga() {
        var c = document.getElementById("upgradeKeluargaContainer");
        if (!c) return;
        var total = 0;
        c.querySelectorAll(".hotel-upgrade-item").forEach(function (hi) {
            var cb = hi.querySelector(".upgrade-hotel-checkbox");
            if (!cb || !cb.checked) return;
            hi.querySelectorAll(".kamar-item").forEach(function (it) {
                var kcb = it.querySelector(".kamar-checkbox");
                if (!kcb || !kcb.checked) return;
                var j = parseInt(it.querySelector(".kamar-jumlah").value) || 1;
                var h =
                    parseInt(
                        (it.querySelector(".kamar-harga").value || "")
                            .replace(/^Rp\s*/, "")
                            .replace(/\./g, ""),
                    ) || 0;
                var d = parseInt(it.querySelector(".kamar-durasi").value) || 1;
                total += j * h * d;
            });
        });
        var te = document.getElementById("totalUpgradeKeluarga");
        if (te) te.textContent = "Rp " + total.toLocaleString("id-ID");
        var jml = document.querySelectorAll(".jamaah-row").length;
        var per = jml > 0 ? Math.round(total / jml) : 0;
        var pe = document.getElementById("perJamaahUpgradeKeluarga");
        if (pe) pe.textContent = "Rp " + per.toLocaleString("id-ID");
    }

    // ===== UPGRADE JAMAAH =====
    window.toggleUpgradeJamaah = function (index) {
        var c = document.getElementById("upgradeJamaahContainer-" + index);
        if (c) c.classList.toggle("hidden");
    };
    window.toggleHotelDetailJamaah = function (jamaahIndex, hotelId) {
        var cb = document.querySelector(
            '.upgrade-hotel-checkbox[data-scope="jamaah-' +
                jamaahIndex +
                '"][data-hotel-id="' +
                hotelId +
                '"]',
        );
        var c = document.getElementById(
            "hotelDetailContainer-" + jamaahIndex + "-" + hotelId,
        );
        if (!cb || !c) return;
        if (cb.checked) {
            c.classList.remove("hidden");
            loadKamarsUpgrade("jamaah-" + jamaahIndex, hotelId, c);
        } else {
            c.classList.add("hidden");
            c.innerHTML =
                '<div class="text-xs text-gray-400 text-center py-2">Pilih hotel untuk memuat kamar</div>';
            removeUpgradeHiddenInputs("jamaah-" + jamaahIndex, hotelId);
            updateTotalUpgradeJamaah(jamaahIndex);
        }
    };
    function updateTotalUpgradeJamaah(jamaahIndex) {
        var c = document.getElementById(
            "upgradeJamaahContainer-" + jamaahIndex,
        );
        if (!c) return;
        var total = 0;
        c.querySelectorAll(".hotel-upgrade-item").forEach(function (hi) {
            var cb = hi.querySelector(".upgrade-hotel-checkbox");
            if (!cb || !cb.checked) return;
            hi.querySelectorAll(".kamar-item").forEach(function (it) {
                var kcb = it.querySelector(".kamar-checkbox");
                if (!kcb || !kcb.checked) return;
                var j = parseInt(it.querySelector(".kamar-jumlah").value) || 1;
                var h =
                    parseInt(
                        (it.querySelector(".kamar-harga").value || "")
                            .replace(/^Rp\s*/, "")
                            .replace(/\./g, ""),
                    ) || 0;
                var d = parseInt(it.querySelector(".kamar-durasi").value) || 1;
                total += j * h * d;
            });
        });
        var te = document.getElementById("totalUpgradeJamaah-" + jamaahIndex);
        if (te) te.textContent = "Rp " + total.toLocaleString("id-ID");
    }

    // ===== LOAD KAMARS =====
    function loadKamarsUpgrade(scope, hotelId, container) {
        container.innerHTML =
            '<div class="text-xs text-gray-400 text-center py-2"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat tipe kamar...</div>';
        fetch(KDATA.routes.getKamarsByHotel + "/" + hotelId)
            .then(function (r) {
                return r.json();
            })
            .then(function (data) {
                if (data.count === 0) {
                    container.innerHTML =
                        '<div class="text-xs text-gray-400 text-center py-2">Hotel ini belum memiliki data kamar.</div>';
                    return;
                }
                var subtotalClass = "subtotal-hotel-" + scope + "-" + hotelId;
                container.innerHTML =
                    '<div class="grid grid-cols-1 gap-2">' +
                    data.html +
                    "</div>" +
                    '<div class="mt-1 flex items-center justify-between bg-orange-50 rounded px-2 py-1">' +
                    '<span class="text-[10px] font-medium text-gray-600">Subtotal hotel ini:</span>' +
                    '<span class="text-xs font-bold text-orange-600 ' +
                    subtotalClass +
                    '">Rp 0</span></div>';

                container
                    .querySelectorAll(".kamar-checkbox")
                    .forEach(function (cb) {
                        cb.addEventListener("change", function () {
                            var parent = cb.closest(".kamar-item");
                            var inputs = parent.querySelectorAll(
                                "input:not(.kamar-checkbox)",
                            );
                            inputs.forEach(function (input) {
                                input.disabled = !cb.checked;
                                input.classList.toggle(
                                    "opacity-50",
                                    !cb.checked,
                                );
                                input.classList.toggle(
                                    "bg-gray-50",
                                    !cb.checked,
                                );
                            });
                            updateUpgradeHiddenInputs(
                                scope,
                                hotelId,
                                container,
                            );
                            recalcUpgradeScope(scope);
                        });
                    });
                container
                    .querySelectorAll(
                        ".kamar-jumlah, .kamar-harga, .kamar-durasi",
                    )
                    .forEach(function (input) {
                        input.addEventListener("change", function () {
                            updateUpgradeHiddenInputs(
                                scope,
                                hotelId,
                                container,
                            );
                            recalcUpgradeScope(scope);
                        });
                        input.addEventListener("keyup", function () {
                            updateUpgradeHiddenInputs(
                                scope,
                                hotelId,
                                container,
                            );
                            recalcUpgradeScope(scope);
                        });
                    });
                restoreExistingUpgrades(scope, hotelId, container);
            })
            .catch(function (err) {
                console.error(err);
                container.innerHTML =
                    '<div class="text-xs text-red-500 text-center py-2">Gagal memuat kamar.</div>';
            });
    }

    function restoreExistingUpgrades(scope, hotelId, container) {
        var list = [];
        if (scope === "keluarga") list = KDATA.existingUpgradeKeluarga;
        else if (scope.indexOf("jamaah-") === 0) {
            var idx = parseInt(scope.replace("jamaah-", ""));
            var row = document.getElementById("jamaah-row-" + idx);
            if (row) {
                var idInp = row.querySelector('input[name$="[id_jamaah]"]');
                var idJ = idInp ? idInp.value : null;
                if (idJ && KDATA.existingUpgradeJamaah[idJ])
                    list = KDATA.existingUpgradeJamaah[idJ];
            }
        }
        var matches = (list || []).filter(function (u) {
            return parseInt(u.id_hotel) === parseInt(hotelId);
        });
        if (matches.length === 0) {
            updateUpgradeHiddenInputs(scope, hotelId, container);
            recalcUpgradeScope(scope);
            return;
        }
        container.querySelectorAll(".kamar-item").forEach(function (it) {
            var cb = it.querySelector(".kamar-checkbox");
            if (!cb) return;
            var m = matches.find(function (u) {
                return parseInt(u.id_kamar) === parseInt(cb.value);
            });
            if (m) {
                cb.checked = true;
                var j = it.querySelector(".kamar-jumlah");
                var h = it.querySelector(".kamar-harga");
                var d = it.querySelector(".kamar-durasi");
                if (j) j.value = m.jumlah_kamar;
                if (h)
                    h.value =
                        "Rp " +
                        parseInt(m.harga_per_malam).toLocaleString("id-ID");
                if (d) d.value = m.durasi_menginap;
                it.querySelectorAll("input:not(.kamar-checkbox)").forEach(
                    function (input) {
                        input.disabled = false;
                        input.classList.remove("opacity-50", "bg-gray-50");
                    },
                );
            }
        });
        updateUpgradeHiddenInputs(scope, hotelId, container);
        recalcUpgradeScope(scope);
    }

    function recalcUpgradeScope(scope) {
        if (scope === "keluarga") updateTotalUpgradeKeluarga();
        else if (scope.indexOf("jamaah-") === 0)
            updateTotalUpgradeJamaah(parseInt(scope.replace("jamaah-", "")));
    }

    // ===== HIDDEN INPUTS =====
    function updateUpgradeHiddenInputs(scope, hotelId, container) {
        removeUpgradeHiddenInputs(scope, hotelId);
        var parentForm = null,
            prefix = "";
        if (scope === "keluarga") {
            parentForm = document.getElementById("keluargaForm");
            prefix = "upgrade_hotels";
        } else {
            var idx = parseInt(scope.replace("jamaah-", ""));
            parentForm = document.getElementById("jamaah-row-" + idx);
            prefix = "jamaahs[" + idx + "][upgrade_hotels]";
        }
        if (!parentForm) return;
        var subtotal = 0;
        container.querySelectorAll(".kamar-item").forEach(function (it) {
            var cb = it.querySelector(".kamar-checkbox");
            if (!cb || !cb.checked) return;
            var j = parseInt(it.querySelector(".kamar-jumlah").value) || 1;
            var h =
                parseInt(
                    (it.querySelector(".kamar-harga").value || "")
                        .replace(/^Rp\s*/, "")
                        .replace(/\./g, ""),
                ) || 0;
            var d = parseInt(it.querySelector(".kamar-durasi").value) || 1;
            subtotal += j * h * d;
            var key = prefix + "[" + hotelId + "_" + cb.value + "]";
            var tipe = cb.dataset.tipe || "";
            var grp = document.createElement("div");
            grp.className = "upgrade-hidden-group";
            grp.dataset.scope = scope;
            grp.dataset.hotelId = hotelId;
            grp.innerHTML =
                '<input type="hidden" name="' +
                key +
                '[id_hotel]" value="' +
                hotelId +
                '">' +
                '<input type="hidden" name="' +
                key +
                '[id_kamar]" value="' +
                cb.value +
                '">' +
                '<input type="hidden" name="' +
                key +
                '[tipe_kamar]" value="' +
                tipe +
                '">' +
                '<input type="hidden" name="' +
                key +
                '[jumlah_kamar]" value="' +
                j +
                '">' +
                '<input type="hidden" name="' +
                key +
                '[durasi_menginap]" value="' +
                d +
                '">' +
                '<input type="hidden" name="' +
                key +
                '[harga_per_malam]" value="' +
                h +
                '">';
            parentForm.appendChild(grp);
        });
        var se = container.querySelector(
            ".subtotal-hotel-" + scope + "-" + hotelId,
        );
        if (se) se.textContent = "Rp " + subtotal.toLocaleString("id-ID");
    }

    function removeUpgradeHiddenInputs(scope, hotelId) {
        var parentForm =
            scope === "keluarga"
                ? document.getElementById("keluargaForm")
                : document.getElementById(
                      "jamaah-row-" + parseInt(scope.replace("jamaah-", "")),
                  );
        if (!parentForm) return;
        parentForm
            .querySelectorAll(
                '.upgrade-hidden-group[data-scope="' +
                    scope +
                    '"][data-hotel-id="' +
                    hotelId +
                    '"]',
            )
            .forEach(function (el) {
                el.remove();
            });
    }

    // ===== ADD JAMAAH =====
    window.addJamaah = function () {
        var container = document.getElementById("jamaahContainer");
        var row = document.createElement("div");
        row.className =
            "jamaah-row bg-gray-50 rounded-xl p-4 mb-3 border border-gray-200";
        var idx = jamaahIndex;
        row.id = "jamaah-row-" + idx;

        var hotelOpts = "";
        KDATA.hotelOptions.forEach(function (hotel) {
            var hid = hotel.id_hotel;
            var nama = hotel.nama_hotel || "";
            var kota = hotel.kota || "-";
            var bintang = hotel.bintang_text || "";
            hotelOpts +=
                '<div class="hotel-upgrade-item border border-gray-200 rounded-lg" data-hotel-id="' +
                hid +
                '" data-scope="jamaah-' +
                idx +
                '">' +
                '<label class="flex items-center gap-2 p-2 cursor-pointer hover:bg-gray-50 rounded-t-lg">' +
                '<input type="checkbox" class="upgrade-hotel-checkbox w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500" data-hotel-id="' +
                hid +
                '" data-scope="jamaah-' +
                idx +
                '" onchange="toggleHotelDetailJamaah(' +
                idx +
                ", " +
                hid +
                ')">' +
                '<div class="flex-1"><p class="text-sm font-medium text-gray-700">' +
                nama +
                "</p>" +
                '<p class="text-xs text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i> ' +
                kota +
                (bintang ? " \u00b7 " + bintang : "") +
                "</p></div></label>" +
                '<div id="hotelDetailContainer-' +
                idx +
                "-" +
                hid +
                '" class="hidden border-t border-gray-100 p-2 space-y-2">' +
                '<div class="text-xs text-gray-400 text-center py-2"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat tipe kamar...</div></div></div>';
        });

        var hubOpts = "";
        KDATA.hubunganOptions.forEach(function (opt) {
            hubOpts += '<option value="' + opt + '">' + opt + "</option>";
        });

        var kotaOpts = "";
        KDATA.kotaAsals.forEach(function (kota) {
            kotaOpts +=
                '<option value="' +
                kota.nama_kota +
                '" data-pulau="' +
                (kota.pulau || "") +
                '" data-bandara="' +
                (kota.bandara_terdekat || "") +
                '">' +
                kota.nama_kota +
                " - " +
                (kota.provinsi || "") +
                "</option>";
        });

        var html = "";
        html += '<div class="flex items-center justify-between mb-3">';
        html +=
            '<span class="text-sm font-medium text-gray-700">Jamaah #' +
            (idx + 1) +
            "</span>";
        html +=
            '<button type="button" onclick="removeJamaah(this)" class="text-red-500 hover:text-red-700 text-sm"><i class="fas fa-times"></i> Hapus</button>';
        html += "</div>";
        html +=
            '<input type="hidden" name="jamaahs[' +
            idx +
            '][id_jamaah]" value="">';

        // Baris 1
        html += '<div class="grid grid-cols-1 md:grid-cols-3 gap-3">';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label><input type="text" name="jamaahs[' +
            idx +
            '][nama_lengkap]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required></div>';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">NIK <span class="text-red-500">*</span></label><input type="text" name="jamaahs[' +
            idx +
            '][nik]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" placeholder="16 digit NIK" required></div>';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Nama Ayah <span class="text-red-500">*</span></label><input type="text" name="jamaahs[' +
            idx +
            '][nama_ayah]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required></div>';
        html += "</div>";

        // Baris 2
        html += '<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Pekerjaan <span class="text-red-500">*</span></label><input type="text" name="jamaahs[' +
            idx +
            '][pekerjaan]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required></div>';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label><select name="jamaahs[' +
            idx +
            '][jenis_kelamin]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required><option value="">-- Pilih --</option><option value="L">Laki-laki</option><option value="P">Perempuan</option></select></div>';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Status Kawin <span class="text-red-500">*</span></label><select name="jamaahs[' +
            idx +
            '][status_kawin]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required><option value="">-- Pilih --</option><option value="Belum Kawin">Belum Kawin</option><option value="Kawin">Kawin</option><option value="Cerai Hidup">Cerai Hidup</option><option value="Cerai Mati">Cerai Mati</option></select></div>';
        html += "</div>";

        // Baris 3
        html += '<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Telepon <span class="text-red-500">*</span></label><input type="text" name="jamaahs[' +
            idx +
            '][telepon]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" placeholder="08xxxxxxxxxx" required></div>';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">WhatsApp <span class="text-red-500">*</span></label><input type="text" name="jamaahs[' +
            idx +
            '][wa]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" placeholder="08xxxxxxxxxx" required></div>';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Tempat Lahir <span class="text-red-500">*</span></label><input type="text" name="jamaahs[' +
            idx +
            '][tempat_lahir]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm wajib-jamaah" required></div>';
        html += "</div>";

        // Baris 4
        html += '<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Lahir</label><input type="date" name="jamaahs[' +
            idx +
            '][tanggal_lahir]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"></div>';
        html += "</div>";

        // Alamat
        html += '<div class="mt-3 pt-3 border-t border-gray-200">';
        html +=
            '<h6 class="text-xs font-semibold text-gray-700 mb-2 flex items-center"><i class="fas fa-map-marker-alt text-yellow-500 mr-1.5"></i> Alamat Domisili</h6>';
        html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-3">';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Hubungan Keluarga</label><select name="jamaahs[' +
            idx +
            '][hubungan_keluarga]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"><option value="">-- Pilih Hubungan --</option>' +
            hubOpts +
            "</select></div>";
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Alamat</label><input type="text" name="jamaahs[' +
            idx +
            '][alamat]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" placeholder="Jl. / RT / RW / No."></div>';
        html += "</div>";
        html += '<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Kota / Kabupaten</label><input type="text" name="jamaahs[' +
            idx +
            '][kota_kabupaten]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" placeholder="Kota/Kabupaten"></div>';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Kecamatan</label><input type="text" name="jamaahs[' +
            idx +
            '][kecamatan]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" placeholder="Kecamatan"></div>';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Kelurahan / Desa</label><input type="text" name="jamaahs[' +
            idx +
            '][kelurahan]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" placeholder="Kelurahan/Desa"></div>';
        html += "</div></div>";

        // Keberangkatan
        html += '<div class="mt-3 pt-3 border-t border-gray-200">';
        html +=
            '<div class="bg-blue-50 rounded-lg p-3 border border-blue-100">';
        html +=
            '<h6 class="text-xs font-semibold text-blue-700 mb-2 flex items-center"><i class="fas fa-plane text-blue-500 mr-1.5"></i> Keberangkatan</h6>';
        html += '<div class="grid grid-cols-1 md:grid-cols-3 gap-3">';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Kota Asal</label><select name="jamaahs[' +
            idx +
            '][kota_asal]" class="kota-asal-select w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm bg-white"><option value="">-- Pilih Kota Asal --</option>' +
            kotaOpts +
            "</select></div>";
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Pulau</label><input type="text" name="jamaahs[' +
            idx +
            '][pulau]" class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm cursor-not-allowed" readonly placeholder="Otomatis"></div>';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Bandara Keberangkatan</label><input type="text" name="jamaahs[' +
            idx +
            '][bandara_keberangkatan]" class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-sm cursor-not-allowed" readonly placeholder="Otomatis"></div>';
        html += "</div></div></div>";

        // Passport
        html += '<div class="mt-3 pt-3 border-t border-gray-200">';
        html +=
            '<h6 class="text-xs font-semibold text-gray-700 mb-2 flex items-center"><i class="fas fa-passport text-yellow-500 mr-1.5"></i> Passport & Dokumen</h6>';
        html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-3">';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Nomor Paspor</label><input type="text" name="jamaahs[' +
            idx +
            '][nomor_paspor]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"></div>';
        html +=
            '<div><label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer mt-6"><input type="checkbox" name="jamaahs[' +
            idx +
            '][is_kepala_keluarga]" value="1" class="w-4 h-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500"><span>Kepala Keluarga / Kelompok</span></label></div>';
        html += "</div>";
        html += '<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Terbit Paspor</label><input type="date" name="jamaahs[' +
            idx +
            '][paspor_terbit]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"></div>';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Berakhir Paspor</label><input type="date" name="jamaahs[' +
            idx +
            '][paspor_expired]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"></div>';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Diterbitkan Di</label><input type="text" name="jamaahs[' +
            idx +
            '][paspor_diterbitkan_di]" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" placeholder="Kota/Negara"></div>';
        html += "</div>";

        // Pembuatan Passport
        html += '<div class="mt-3 pt-3 border-t border-gray-200">';
        html +=
            '<div class="mb-3"><label class="block text-xs font-medium text-gray-600 mb-1.5">Apakah ada Pembuatan Passport? <span class="text-red-500">*</span></label>';
        html +=
            '<div class="inline-flex rounded-lg overflow-hidden border border-gray-300">';
        html +=
            '<button type="button" onclick="setPembuatanPassportKeluarga(' +
            idx +
            ', 1)" id="btnYa-' +
            idx +
            '" class="px-6 py-2 text-sm font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors"><i class="fas fa-check mr-1"></i> YA</button>';
        html +=
            '<button type="button" onclick="setPembuatanPassportKeluarga(' +
            idx +
            ', 0)" id="btnTidak-' +
            idx +
            '" class="px-6 py-2 text-sm font-medium bg-red-500 text-white transition-colors border-l border-gray-300"><i class="fas fa-times mr-1"></i> TIDAK</button>';
        html += "</div>";
        html +=
            '<input type="hidden" name="jamaahs[' +
            idx +
            '][is_pembuatan_passport]" id="is_pembuatan_passport-' +
            idx +
            '" value="0">';
        html +=
            '<p class="text-xs text-gray-400 mt-1"><i class="fas fa-info-circle mr-1"></i>Pilih YA jika ada pendapatan perusahaan.</p></div>';
        html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-3">';
        html +=
            '<div><label class="block text-xs font-medium text-gray-600 mb-1">Pembayaran Passport</label><div class="relative"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span><input type="text" name="jamaahs[' +
            idx +
            '][pendampingan_fee]" value="0" oninput="formatRupiah(this); updateTotalPendampingan(' +
            idx +
            ')" onfocus="handleZeroFocus(this)" onblur="handleZeroBlur(this)" class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" placeholder="0"></div></div>';
        html +=
            '<div id="feePetugasContainer-' +
            idx +
            '" class="hidden"><label class="block text-xs font-medium text-gray-600 mb-1">Pendapatan Perusahaan</label><div class="relative"><span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span><input type="text" name="jamaahs[' +
            idx +
            '][pendampingan_fee_petugas]" id="pendampingan_fee_petugas-' +
            idx +
            '" value="0" oninput="formatRupiah(this); updateTotalPendampingan(' +
            idx +
            ')" onfocus="handleZeroFocus(this)" onblur="handleZeroBlur(this)" class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm" placeholder="0"></div></div>';
        html += "</div>";
        html +=
            '<div class="mt-2 text-xs text-gray-500">Total Pembuatan Passport: <span class="font-bold text-blue-600" id="total-pendampingan-' +
            idx +
            '">Rp 0</span></div>';
        html += "</div></div>";

        // Upgrade Jamaah
        html += '<div class="mt-3 pt-3 border-t border-gray-200">';
        html += '<div class="flex items-center justify-between mb-2">';
        html +=
            '<h6 class="text-xs font-semibold text-gray-700 flex items-center"><i class="fas fa-hotel text-orange-500 mr-1.5"></i> Upgrade Hotel & Kamar Jamaah</h6>';
        html +=
            '<button type="button" onclick="toggleUpgradeJamaah(' +
            idx +
            ')" class="text-xs text-blue-500 hover:text-blue-700 font-medium"><i class="fas fa-plus mr-1"></i> Atur Hotel</button>';
        html += "</div>";
        html +=
            '<div id="upgradeJamaahContainer-' +
            idx +
            '" class="hidden bg-orange-50 rounded-lg p-3 border border-orange-200">';
        html +=
            '<p class="text-xs text-orange-700 mb-2"><i class="fas fa-info-circle mr-1"></i>Biaya upgrade ini hanya dibebankan ke jamaah ini.</p>';
        html +=
            '<div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg bg-white p-2 space-y-2">' +
            hotelOpts +
            "</div>";
        html +=
            '<div class="mt-2 flex items-center justify-between bg-white rounded-lg border border-orange-200 px-3 py-2"><span class="text-xs font-medium text-gray-700"><i class="fas fa-calculator mr-1 text-orange-500"></i>Total Upgrade Jamaah:</span><span id="totalUpgradeJamaah-' +
            idx +
            '" class="text-sm font-bold text-orange-600">Rp 0</span></div>';
        html += "</div></div>";

        // File Upload
        html +=
            '<div class="grid grid-cols-1 md:grid-cols-4 gap-3 mt-3 pt-3 border-t border-gray-200">';
        [
            "file_ktp_kk|File KTP/KK",
            "file_vaksin|File Vaksin",
            "file_visa|File Visa",
            "file_paspor|File Paspor",
        ].forEach(function (item) {
            var parts = item.split("|");
            var field = parts[0],
                label = parts[1];
            html +=
                '<div><label class="block text-xs font-medium text-gray-600 mb-1">' +
                label +
                "</label>";
            html +=
                '<input type="file" name="jamaahs[' +
                idx +
                "][" +
                field +
                ']" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">';
            html +=
                '<p class="text-xs text-gray-400 mt-1">PDF/JPG/PNG Max 2MB</p></div>';
        });
        html += "</div>";

        row.innerHTML = html;
        container.appendChild(row);

        var kotaSelect = row.querySelector(".kota-asal-select");
        if (kotaSelect) {
            kotaSelect.addEventListener("change", function () {
                var opt = this.options[this.selectedIndex];
                var p = row.querySelector('input[name$="[pulau]"]');
                var b = row.querySelector(
                    'input[name$="[bandara_keberangkatan]"]',
                );
                if (p) p.value = opt.dataset.pulau || "";
                if (b) b.value = opt.dataset.bandara || "";
            });
        }

        jamaahIndex++;
        window.hitungDiskonKelompok();
        updateTotalUpgradeKeluarga();
        window.updateFeeAgentPerJamaah();
    };

    window.removeJamaah = function (button) {
        var row = button.closest(".jamaah-row");
        var container = document.getElementById("jamaahContainer");
        if (container.children.length > 1) {
            row.remove();
            container
                .querySelectorAll(".jamaah-row")
                .forEach(function (r, idx) {
                    var label = r.querySelector(".text-sm.font-medium");
                    if (label) label.textContent = "Jamaah #" + (idx + 1);
                });
            window.hitungDiskonKelompok();
            updateTotalUpgradeKeluarga();
            window.updateFeeAgentPerJamaah();
        } else {
            alert("Minimal harus ada 1 jamaah!");
        }
    };

    // ===== DOM READY =====
    document.addEventListener("DOMContentLoaded", function () {
        var filterTahun = document.getElementById("filter-tahun");
        if (filterTahun)
            filterTahun.addEventListener("change", function () {
                renderKeberangkatanByTahun(this.value);
            });

        var produk = document.getElementById("produk_paket");
        if (produk)
            produk.addEventListener("change", function () {
                OLD_ID_HARGA = 0;
                OLD_ID_KEBERANGKATAN = 0;
                loadKeberangkatan();
            });

        var idDiskon = document.getElementById("id_diskon");
        if (idDiskon)
            idDiskon.addEventListener("change", window.hitungDiskonKelompok);

        document.addEventListener("change", function (e) {
            if (e.target && e.target.classList.contains("kota-asal-select")) {
                var opt = e.target.options[e.target.selectedIndex];
                var row = e.target.closest(".jamaah-row");
                if (row) {
                    var p = row.querySelector('input[name$="[pulau]"]');
                    var b = row.querySelector(
                        'input[name$="[bandara_keberangkatan]"]',
                    );
                    if (p) p.value = opt.dataset.pulau || "";
                    if (b) b.value = opt.dataset.bandara || "";
                }
            }
        });

        document.addEventListener("input", function (e) {
            if (
                e.target &&
                e.target.name &&
                e.target.name.indexOf("pendampingan_fee") !== -1
            ) {
                var m = e.target.name.match(/\d+/);
                if (m) window.updateTotalPendampingan(parseInt(m[0]));
            }
        });

        document.querySelectorAll(".kota-asal-select").forEach(function (sel) {
            if (sel.value) sel.dispatchEvent(new Event("change"));
        });
        document.querySelectorAll(".jamaah-row").forEach(function (row, idx) {
            window.updateTotalPendampingan(idx);
        });

        if (produk && produk.value) loadKeberangkatan();
        window.hitungDiskonKelompok();
        window.updateFeeAgentPerJamaah();

        setTimeout(function () {
            if (KDATA.existingUpgradeKeluarga.length > 0) {
                document
                    .querySelectorAll(
                        "#upgradeKeluargaContainer .upgrade-hotel-checkbox:checked",
                    )
                    .forEach(function (cb) {
                        cb.dispatchEvent(new Event("change"));
                    });
            }
            document
                .querySelectorAll(".jamaah-row")
                .forEach(function (row, idx) {
                    var cbs = row.querySelectorAll(
                        "#upgradeJamaahContainer-" +
                            idx +
                            " .upgrade-hotel-checkbox:checked",
                    );
                    if (cbs.length > 0) {
                        var c = document.getElementById(
                            "upgradeJamaahContainer-" + idx,
                        );
                        if (c) c.classList.remove("hidden");
                        cbs.forEach(function (cb) {
                            cb.dispatchEvent(new Event("change"));
                        });
                    }
                });
        }, 300);

        var form = document.getElementById("keluargaForm");
        if (form) {
            form.addEventListener("submit", function (e) {
                if (!validateJamaahForm()) {
                    e.preventDefault();
                    return false;
                }
                ["fee_agent"].forEach(function (id) {
                    var inp = document.getElementById(id);
                    if (inp) inp.value = inp.value.replace(/\./g, "");
                });
                document
                    .querySelectorAll(
                        '[name$="[pendampingan_fee]"], [name$="[pendampingan_fee_petugas]"]',
                    )
                    .forEach(function (input) {
                        if (input.value)
                            input.value = input.value.replace(/\./g, "");
                    });

                var selKeb = document.getElementById("keberangkatan-select");
                if (selKeb && selKeb.value) {
                    var k = globalKeberangkatanList.find(function (item) {
                        return parseInt(item.id) === parseInt(selKeb.value);
                    });
                    var selIdKeb = parseInt(selKeb.value);
                    var hargaSel = document.querySelector(
                        ".harga-bulanan-select",
                    );
                    var selHargaFull = false;
                    if (hargaSel && hargaSel.value) {
                        var opt = hargaSel.options[hargaSel.selectedIndex];
                        var optId = parseInt(hargaSel.value);
                        selHargaFull =
                            opt.dataset.isFull === "1" &&
                            optId !== OLD_ID_HARGA;
                    }
                    var isOldKeb = selIdKeb === OLD_ID_KEBERANGKATAN;
                    var kebFull = k && k.kuota_default_is_full && !isOldKeb;
                    if (kebFull || selHargaFull) {
                        e.preventDefault();
                        alert(
                            "Kuota bulan/tahun yang dipilih sudah PENUH! Silakan pilih lain.",
                        );
                        return false;
                    }
                }
            });
        }
    });
})();
