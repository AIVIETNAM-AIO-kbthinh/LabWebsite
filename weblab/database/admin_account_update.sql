-- Optional helper: reset the main admin account manually if you cannot log in.
-- Change the username/email/password hash before running if needed.
-- Default temporary password below is: isilab@2024
USE `isilab`;
UPDATE `admins`
SET `username`='admin',
    `email`='admin@isilab.local',
    `password`='$2y$10$t5X0zo4F1PZb/bBSFs.2fuafUdmUE//zKW4WC9kfFyeoqiSCZVdoC',
    `full_name`='Lab Administrator'
WHERE `id`=1;
