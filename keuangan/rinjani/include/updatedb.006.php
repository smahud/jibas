<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
 * @notes:
 *
 * Copyright (C) 2024 JIBAS (http://www.jibas.net)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?php
/*
 * 2026-05-28: Buat trigger database untuk Online Payment Rinjani
 */
if (IsTriggerExist($db, "jbsakad", "departemen", "jssync_departemen_ains"))
{
    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_departemen_ains";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_departemen_aupd";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_departemen_adel";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsakad", "departemen", "jssync_departemen_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_departemen_ains2 AFTER INSERT ON jbsakad.departemen
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                    INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.departemen', iddata = NEW.replid, state = 'I', dataload = 1
                        ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_departemen_aupd2 AFTER UPDATE ON jbsakad.departemen
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.departemen', iddata = OLD.replid, state = 'U', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_departemen_adel2 AFTER DELETE ON jbsakad.departemen
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.departemen', iddata = OLD.replid, state = 'D', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
                END IF;
            END";
    ExecIgnore($db, $sql);
}

if (IsTriggerExist($db, "jbsakad", "tahunajaran", "jssync_tahunajaran_ains"))
{
    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_tahunajaran_ains";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_tahunajaran_aupd";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_tahunajaran_adel";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsakad", "tahunajaran", "jssync_tahunajaran_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_tahunajaran_ains2 AFTER INSERT ON jbsakad.tahunajaran
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                    INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.tahunajaran', iddata = NEW.replid, state = 'I', dataload = 1
                        ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_tahunajaran_aupd2 AFTER UPDATE ON jbsakad.tahunajaran
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.tahunajaran', iddata = OLD.replid, state = 'U', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_tahunajaran_adel2 AFTER DELETE ON jbsakad.tahunajaran
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.tahunajaran', iddata = OLD.replid, state = 'D', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
                END IF;
            END";
    ExecIgnore($db, $sql);
}

if (IsTriggerExist($db, "jbsakad", "tingkat", "jssync_tingkat_ains"))
{
    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_tingkat_ains";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_tingkat_aupd";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_tingkat_adel";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsakad", "tingkat", "jssync_tingkat_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_tingkat_ains2 AFTER INSERT ON jbsakad.tingkat
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                    INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.tingkat', iddata = NEW.replid, state = 'I', dataload = 1
                        ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_tingkat_aupd2 AFTER UPDATE ON jbsakad.tingkat
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.tingkat', iddata = OLD.replid, state = 'U', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_tingkat_adel2 AFTER DELETE ON jbsakad.tingkat
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.tingkat', iddata = OLD.replid, state = 'D', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
                END IF;
            END";
    ExecIgnore($db, $sql);
}

if (IsTriggerExist($db, "jbsakad", "angkatan", "jssync_angkatan_ains"))
{
    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_angkatan_ains";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_angkatan_aupd";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_angkatan_adel";
    ExecIgnore($db, $sql);
}


if (!IsTriggerExist($db, "jbsakad", "angkatan", "jssync_angkatan_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_angkatan_ains2 AFTER INSERT ON jbsakad.angkatan
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                    INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.angkatan', iddata = NEW.replid, state = 'I', dataload = 1
                        ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_angkatan_aupd2 AFTER UPDATE ON jbsakad.angkatan
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.angkatan', iddata = OLD.replid, state = 'U', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_angkatan_adel2 AFTER DELETE ON jbsakad.angkatan
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.angkatan', iddata = OLD.replid, state = 'D', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
                END IF;
            END";
    ExecIgnore($db, $sql);
}

if (IsTriggerExist($db, "jbsakad", "kelas", "jssync_kelas_ains"))
{
    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_kelas_ains";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_kelas_aupd";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_kelas_adel";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsakad", "kelas", "jssync_kelas_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_kelas_ains2 AFTER INSERT ON jbsakad.kelas
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                    INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.kelas', iddata = NEW.replid, state = 'I', dataload = 1
                        ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_kelas_aupd2 AFTER UPDATE ON jbsakad.kelas
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.kelas', iddata = OLD.replid, state = 'U', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_kelas_adel2 AFTER DELETE ON jbsakad.kelas
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.kelas', iddata = OLD.replid, state = 'D', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
                END IF;
            END";
    ExecIgnore($db, $sql);
}


if (IsTriggerExist($db, "jbsakad", "semester", "jssync_semester_ains"))
{
    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_semester_ains";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_semester_aupd";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_semester_adel";
    ExecIgnore($db, $sql);
}


if (!IsTriggerExist($db, "jbsakad", "semester", "jssync_semester_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_semester_ains2 AFTER INSERT ON jbsakad.semester
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                    INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.semester', iddata = NEW.replid, state = 'I', dataload = 1
                        ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_semester_aupd2 AFTER UPDATE ON jbsakad.semester
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.semester', iddata = OLD.replid, state = 'U', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_semester_adel2 AFTER DELETE ON jbsakad.semester
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.semester', iddata = OLD.replid, state = 'D', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
                END IF;
            END";
    ExecIgnore($db, $sql);
}

if (IsTriggerExist($db, "jbsakad", "siswa", "jssync_siswa_ains"))
{
    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_siswa_ains";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_siswa_aupd";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsakad.jssync_siswa_adel";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsakad", "siswa", "jssync_siswa_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_siswa_ains2 AFTER INSERT ON jbsakad.siswa
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                    INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.siswa', iddata = NEW.replid, state = 'I', dataload = 1
                        ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_siswa_aupd2 AFTER UPDATE ON jbsakad.siswa
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.siswa', iddata = OLD.replid, state = 'U', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsakad.jssync_siswa_adel2 AFTER DELETE ON jbsakad.siswa
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsakad.siswa', iddata = OLD.replid, state = 'D', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
                END IF;
            END";
    ExecIgnore($db, $sql);
}

if (IsTriggerExist($db, "jbssdm", "pegawai", "jssync_pegawai_ains"))
{
    $sql = "DROP TRIGGER IF EXISTS jbssdm.jssync_pegawai_ains";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbssdm.jssync_pegawai_aupd";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbssdm.jssync_pegawai_adel";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbssdm", "pegawai", "jssync_pegawai_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbssdm.jssync_pegawai_ains2 AFTER INSERT ON jbssdm.pegawai
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                    INSERT INTO jbsjs.syncdata2 SET tablename = 'jbssdm.pegawai', iddata = NEW.replid, state = 'I', dataload = 1
                        ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbssdm.jssync_pegawai_aupd2 AFTER UPDATE ON jbssdm.pegawai
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbssdm.pegawai', iddata = OLD.replid, state = 'U', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbssdm.jssync_pegawai_adel2 AFTER DELETE ON jbssdm.pegawai
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbssdm.pegawai', iddata = OLD.replid, state = 'D', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
                END IF;
            END";
    ExecIgnore($db, $sql);
}

if (IsTriggerExist($db, "jbsfina", "bank", "jssync_bank_ains"))
{
    $sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_bank_ains";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_bank_aupd";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_bank_adel";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsfina", "bank2", "jssync_bank2_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_bank2_ains2 AFTER INSERT ON jbsfina.bank2
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                    INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.bank2', iddata = NEW.replid, state = 'I', dataload = 1
                        ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_bank2_aupd2 AFTER UPDATE ON jbsfina.bank2
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.bank2', iddata = OLD.replid, state = 'U', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_bank2_adel2 AFTER DELETE ON jbsfina.bank2
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.bank2', iddata = OLD.replid, state = 'D', dataload = 1
                    ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
                END IF;
            END";
    ExecIgnore($db, $sql);
}

if (IsTriggerExist($db, "jbsfina", "infobayar", "jssync_infobayar_ains"))
{
    $sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_infobayar_ains";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_infobayar_aupd";
    ExecIgnore($db, $sql);

    $sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_infobayar_adel";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsfina", "infobayar2", "jssync_infobayar2_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_infobayar2_ains2 AFTER INSERT ON jbsfina.infobayar2
            FOR EACH ROW BEGIN
                IF @DISABLE_TRIGGER IS NULL THEN
                    INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.infobayar2', iddata = NEW.replid, state = 'I', dataload = 1
                        ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
                END IF;
            END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_infobayar2_aupd2 AFTER UPDATE ON jbsfina.infobayar2
			FOR EACH ROW BEGIN
				IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.infobayar2', iddata = OLD.replid, state = 'U', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
				END IF;
			END";
    ExecIgnore($db, $sql);

    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_infobayar2_adel2 AFTER DELETE ON jbsfina.infobayar2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.infobayar2', iddata = OLD.replid, state = 'D', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
			  END IF;
			END";
    ExecIgnore($db, $sql);
}

if (IsTriggerExist($db, "jbsfina", "tagihanset", "jssync_tagihanset_ains"))
{
    $sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_tagihanset_ains";
    ExecIgnore($db, $sql);
	
	$sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_tagihanset_aupd";
    ExecIgnore($db, $sql);
	
	$sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_tagihanset_adel";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsfina", "tagihanset2", "jssync_tagihanset2_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_tagihanset2_ains2 AFTER INSERT ON jbsfina.tagihanset2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				 INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.tagihanset2', iddata = NEW.replid, state = 'I', dataload = 1
					 ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_tagihanset2_aupd2 AFTER UPDATE ON jbsfina.tagihanset2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.tagihanset2', iddata = OLD.replid, state = 'U', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_tagihanset2_adel2 AFTER DELETE ON jbsfina.tagihanset2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.tagihanset2', iddata = OLD.replid, state = 'D', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
			  END IF;
			END";
    ExecIgnore($db, $sql);
}

if (IsTriggerExist($db, "jbsfina", "tagihansiswainfo", "jssync_tagihansiswainfo_ains"))
{
    $sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_tagihansiswainfo_ains";
    ExecIgnore($db, $sql);
	
	$sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_tagihansiswainfo_aupd";
    ExecIgnore($db, $sql);
	
	$sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_tagihansiswainfo_adel";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsfina", "tagihansiswainfo2", "jssync_tagihansiswainfo2_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_tagihansiswainfo2_ains2 AFTER INSERT ON jbsfina.tagihansiswainfo2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				 INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.tagihansiswainfo2', iddata = NEW.replid, state = 'I', dataload = 1
					 ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_tagihansiswainfo2_aupd2 AFTER UPDATE ON jbsfina.tagihansiswainfo2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.tagihansiswainfo2', iddata = OLD.replid, state = 'U', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_tagihansiswainfo2_adel2 AFTER DELETE ON jbsfina.tagihansiswainfo2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.tagihansiswainfo2', iddata = OLD.replid, state = 'D', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
			  END IF;
			END";
    ExecIgnore($db, $sql);
}

if (IsTriggerExist($db, "jbsfina", "tagihansiswadata", "jssync_tagihansiswadata_ains"))
{
    $sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_tagihansiswadata_ains";
    ExecIgnore($db, $sql);
	
	$sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_tagihansiswadata_aupd";
    ExecIgnore($db, $sql);
	
	$sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_tagihansiswadata_adel";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsfina", "tagihansiswadata2", "jssync_tagihansiswadata2_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_tagihansiswadata2_ains2 AFTER INSERT ON jbsfina.tagihansiswadata2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				 INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.tagihansiswadata2', iddata = NEW.replid, state = 'I', dataload = 1
					 ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_tagihansiswadata2_aupd2 AFTER UPDATE ON jbsfina.tagihansiswadata2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.tagihansiswadata2', iddata = OLD.replid, state = 'U', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_tagihansiswadata2_adel2 AFTER DELETE ON jbsfina.tagihansiswadata2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.tagihansiswadata2', iddata = OLD.replid, state = 'D', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
			  END IF;
			END";
    ExecIgnore($db, $sql);
}

if (IsTriggerExist($db, "jbsfina", "pgtranslebih", "jssync_pgtranslebih_ains"))
{
    $sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_pgtranslebih_ains";
    ExecIgnore($db, $sql);
	
	$sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_pgtranslebih_aupd";
    ExecIgnore($db, $sql);
	
	$sql = "DROP TRIGGER IF EXISTS jbsfina.jssync_pgtranslebih_adel";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsfina", "pgtranslebih2", "jssync_pgtranslebih2_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_pgtranslebih2_ains2 AFTER INSERT ON jbsfina.pgtranslebih2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				 INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.pgtranslebih2', iddata = NEW.id, state = 'I', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_pgtranslebih2_aupd2 AFTER UPDATE ON jbsfina.pgtranslebih2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.pgtranslebih2', iddata = OLD.id, state = 'U', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_pgtranslebih2_adel2 AFTER DELETE ON jbsfina.pgtranslebih2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.pgtranslebih2', iddata = OLD.id, state = 'D', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
			  END IF;
			END";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsfina", "pgtrans2", "jssync_pgtrans2_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_pgtrans2_ains2 AFTER INSERT ON jbsfina.pgtrans2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				 INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.pgtrans2', iddata = NEW.replid, state = 'I', dataload = 1
					 ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_pgtrans2_aupd2 AFTER UPDATE ON jbsfina.pgtrans2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.pgtrans2', iddata = OLD.replid, state = 'U', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_pgtrans2_adel2 AFTER DELETE ON jbsfina.pgtrans2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.pgtrans2', iddata = OLD.replid, state = 'D', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
			  END IF;
			END";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsfina", "pgtransdata2", "jssync_pgtransdata2_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_pgtransdata2_ains2 AFTER INSERT ON jbsfina.pgtransdata2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				 INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.pgtransdata2', iddata = NEW.replid, state = 'I', dataload = 1
					 ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_pgtransdata2_aupd2 AFTER UPDATE ON jbsfina.pgtransdata2
			FOR EACH ROW BEGIN
				IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.pgtransdata2', iddata = OLD.replid, state = 'U', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
				END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_pgservicefee2_ains2 AFTER INSERT ON jbsfina.pgservicefee2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				 INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.pgservicefee2', iddata = NEW.id, state = 'I', dataload = 1
					 ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
			  END IF;
			END";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsfina", "pgfinishresync2", "jssync_pgfinishresync2_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_pgfinishresync2_ains2 AFTER INSERT ON jbsfina.pgfinishresync2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				 INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.pgfinishresync2', iddata = NEW.id, state = 'I', dataload = 1
					 ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
			  END IF;
			END";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsfina", "pgservicefee2", "jssync_pgservicefee2_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_pgservicefee2_ains2 AFTER INSERT ON jbsfina.pgservicefee2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				 INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.pgservicefee2', iddata = NEW.id, state = 'I', dataload = 1
					 ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_pgservicefee2_aupd2 AFTER UPDATE ON jbsfina.pgservicefee2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.pgservicefee2', iddata = OLD.id, state = 'U', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_pgservicefee2_adel AFTER DELETE ON jbsfina.pgservicefee2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.pgservicefee2', iddata = OLD.id, state = 'D', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
			  END IF;
			END";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsfina", "vasiswa2", "jssync_vasiswa2_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_vasiswa2_ains2 AFTER INSERT ON jbsfina.vasiswa2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				 INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.vasiswa2', iddata = NEW.id, state = 'I', dataload = 1
					 ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_vasiswa2_aupd2 AFTER UPDATE ON jbsfina.vasiswa2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.vasiswa2', iddata = OLD.id, state = 'U', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsfina.jssync_vasiswa2_adel AFTER DELETE ON jbsfina.vasiswa2
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsfina.vasiswa2', iddata = OLD.id, state = 'D', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
			  END IF;
			END";
    ExecIgnore($db, $sql);
}

if (!IsTriggerExist($db, "jbsuser", "hakakses", "jssync_hakakses_ains2"))
{
    $sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsuser.jssync_hakakses_ains2 AFTER INSERT ON jbsuser.hakakses
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				 IF NEW.modul = 'KEUANGAN' THEN	
					INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsuser.hakakses', iddata = NEW.replid, state = 'I', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'I');
				 END IF;     
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsuser.jssync_hakakses_aupd2 AFTER UPDATE ON jbsuser.hakakses
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				 IF NEW.modul = 'KEUANGAN' THEN	
					INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsuser.hakakses', iddata = OLD.replid, state = 'U', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'U');
				 END IF;
			  END IF;
			END";
    ExecIgnore($db, $sql);
	
	$sql = "CREATE DEFINER=`root`@`%` TRIGGER jbsuser.jssync_hakakses_adel2 AFTER DELETE ON jbsuser.hakakses
			FOR EACH ROW BEGIN
			  IF @DISABLE_TRIGGER IS NULL THEN
				 IF OLD.modul = 'KEUANGAN' THEN
				INSERT INTO jbsjs.syncdata2 SET tablename = 'jbsuser.hakakses', iddata = OLD.replid, state = 'D', dataload = 1
					ON DUPLICATE KEY UPDATE state = CONCAT(state, 'D');
				 END IF;     
			  END IF;
			END";
    ExecIgnore($db, $sql);
}
?>