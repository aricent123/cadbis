DROP TABLE IF EXISTS `holidays`;
DROP TABLE IF EXISTS `blacklist`;
DROP TABLE IF EXISTS `prices`;
ALTER TABLE `packets` 
 DROP COLUMN `prefix`,
 DROP COLUMN `deposit`,
 DROP COLUMN `credit`,
 DROP COLUMN `tos`,
 DROP COLUMN `do_with_tos`,
 DROP COLUMN `direction`,
 DROP COLUMN `fixed`,
 DROP COLUMN `fixed_cost`,
 DROP COLUMN `activated`,
 DROP COLUMN `activation_time`,
 DROP COLUMN `total_money_limit`,
 DROP COLUMN `month_money_limit`,
 DROP COLUMN `week_money_limit`,
 DROP COLUMN `day_money_limit`,
 DROP COLUMN `huntgroup_name`,
 DROP COLUMN `session_timeout`,
 DROP COLUMN `idle_timeout`,
 DROP COLUMN `allowed_prefixes`,
 DROP COLUMN `framed_ip`,
 DROP COLUMN `framed_mask`,
 DROP COLUMN `no_pass`,
 DROP COLUMN `no_acct`,
 DROP COLUMN `allow_callback`,
 DROP COLUMN `other_params`,
 DROP COLUMN `create_system_user`,
 DROP COLUMN `crypt_method`;

ALTER TABLE `users` 
 DROP COLUMN `crypt_method`,
 DROP COLUMN `deposit`,
 DROP COLUMN `credit`,
 DROP COLUMN `activated`,
 DROP COLUMN `expired`,
 DROP COLUMN `total_money`,
 DROP COLUMN `last_connection`,
 DROP COLUMN `framed_ip`,
 DROP COLUMN `framed_mask`,
 DROP COLUMN `callback_number`
, ROW_FORMAT = DYNAMIC;

ALTER TABLE `packets` ENGINE = InnoDB;


ALTER TABLE `users` 
 ADD COLUMN `simultaneous_use` INTEGER UNSIGNED NOT NULL DEFAULT 1 AFTER `total_traffic`,
 ADD COLUMN `max_total_traffic` BIGINT UNSIGNED NOT NULL DEFAULT 0 AFTER `simultaneous_use`,
 ADD COLUMN `max_month_traffic` BIGINT UNSIGNED NOT NULL DEFAULT 0 AFTER `max_total_traffic`,
 ADD COLUMN `max_week_traffic` BIGINT UNSIGNED NOT NULL DEFAULT 0 AFTER `max_month_traffic`,
 ADD COLUMN `max_day_traffic` BIGINT UNSIGNED NOT NULL DEFAULT 0 AFTER `max_week_traffic`
, ROW_FORMAT = DYNAMIC;

ALTER TABLE `packets` 
 ADD COLUMN `rang` INTEGER UNSIGNED NOT NULL DEFAULT 1 AFTER `prim`,
 ADD COLUMN `exceed_times` INTEGER UNSIGNED NOT NULL DEFAULT 0 AFTER `rang`
, ROW_FORMAT = DYNAMIC;
