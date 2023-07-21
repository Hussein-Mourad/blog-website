SELECT *  FROM blogs.reactions;
SELECT id, postId,users userId, type FROM blogs.reactions where postId = 54 order by type;
INSERT INTO `blogs`.`reactions` (`type`, `postId`, `userId`) VALUES ('sad', '50', '114');
UPDATE `blogs`.`reactions` SET `type` = 'haha' WHERE (`id` = '6') and (`postId` = '54') and (`userId` = '114');
DELETE FROM `blogs`.`reactions` WHERE (`id` = '7') and (`postId` = '54') and (`userId` = '113');
DELETE from reactions where id=9;
INSERT INTO `blogs`.`reactions` (`postId`, `userId`, `type`) VALUES ('54', '114', 'love');
