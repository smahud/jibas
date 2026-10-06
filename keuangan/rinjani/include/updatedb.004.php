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
if (!IsColumnExist($db, "jbsfina", "paymenttrans", "lokasidana"))
{
    $sql = "ALTER TABLE `jbsfina`.`paymenttrans`
              ADD COLUMN `lokasidana` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL AFTER `idtabunganp`";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE `jbsfina`.`paymenttrans`
              ADD INDEX `IX_paymenttrans_lokasidana` (`lokasidana` ASC)";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE `jbsfina`.`paymenttrans`
              ADD CONSTRAINT `FK_paymenttrans_lokasidana`
                  FOREIGN KEY (`lokasidana`)
                  REFERENCES `jbsfina`.`lokasidana` (`kode`)
                  ON DELETE RESTRICT
                  ON UPDATE CASCADE";
    ExecIgnore($db, $sql);
}
?>