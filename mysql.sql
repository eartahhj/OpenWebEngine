CREATE TABLE `admins` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
 `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 `enabled` tinyint(1) DEFAULT 0,
 `username` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `password` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `password_expiration_date` date DEFAULT NULL,
 `level` int(11) DEFAULT NULL,
 `last_access` date DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

CREATE TABLE `admins_groups` (
 `admin_id` int(11) DEFAULT NULL,
 `group_id` int(11) DEFAULT NULL,
 KEY `admin_id_fkey` (`admin_id`),
 KEY `admin_group_id_fkey` (`group_id`),
 CONSTRAINT `admin_group_id_fkey` FOREIGN KEY (`group_id`) REFERENCES `admin_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `admin_id_fkey` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

CREATE TABLE `admin_groups` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
 `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 `enabled` tinyint(1) DEFAULT 0,
 `name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

CREATE TABLE `admin_tokens` (
 `admin_id` int(11) NOT NULL,
 `auth_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `ip` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `timestamp_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
 `timestamp_expiration` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 UNIQUE KEY `auth_token` (`auth_token`),
 KEY `id_admin_fkey` (`admin_id`),
 CONSTRAINT `id_admin_fkey` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

CREATE TABLE `comments` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `text` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `author_name` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `id_user` int(11) DEFAULT NULL,
 `approved` tinyint(1) DEFAULT 0,
 `language` text COLLATE utf8mb4_unicode_ci NOT NULL,
 `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
 `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 `id_page` int(11) DEFAULT NULL,
 `email` text COLLATE utf8mb4_unicode_ci DEFAULT '',
 PRIMARY KEY (`id`),
 KEY `id_page` (`id_page`),
 CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id_page`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

CREATE TABLE `files` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `title_it` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `title_en` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `url_it` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `url_en` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `mimetype` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `sort` int(11) DEFAULT NULL,
 `hidden` tinyint(1) DEFAULT 0,
 `filename` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `language` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
 `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 `admin_creator` int(11) DEFAULT NULL,
 `admin_last_editor` int(11) DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `files_admin_creator_fkey` (`admin_creator`),
 KEY `files_admin_last_editor_fkey` (`admin_last_editor`),
 CONSTRAINT `files_admin_creator_fkey` FOREIGN KEY (`admin_creator`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
 CONSTRAINT `files_admin_last_editor_fkey` FOREIGN KEY (`admin_last_editor`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

CREATE TABLE `files_pages` (
 `id_file` int(11) DEFAULT NULL,
 `id_page` int(11) DEFAULT NULL,
 KEY `id_file_fkey` (`id_file`),
 KEY `id_page_fkey` (`id_page`),
 CONSTRAINT `id_file_fkey` FOREIGN KEY (`id_file`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `id_page_fkey` FOREIGN KEY (`id_page`) REFERENCES `pages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

CREATE TABLE `link_pages_categories` (
 `id_page` int(11) DEFAULT NULL,
 `id_category` int(11) DEFAULT NULL,
 KEY `page_id_fkey` (`id_page`),
 KEY `category_id_fkey` (`id_category`),
 CONSTRAINT `category_id_fkey` FOREIGN KEY (`id_category`) REFERENCES `page_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `page_id_fkey` FOREIGN KEY (`id_page`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

CREATE TABLE `pages` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
 `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 `image` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `title` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
 `title_it` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
 `title_en` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
 `html_it` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
 `html_en` longtext COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `url` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `url_it` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `url_en` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `seo_title_it` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `seo_title_en` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `tags_it` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `tags_en` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `seo_description_it` text COLLATE utf8mb4_unicode_ci NOT NULL,
 `seo_description_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
 `admin_creator` int(11) DEFAULT NULL,
 `admin_last_editor` int(11) DEFAULT NULL,
 `group_assigned` int(11) DEFAULT NULL,
 `category` int(11) DEFAULT NULL,
 `template` int(11) DEFAULT NULL,
 `module` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `empty` tinyint(1) DEFAULT 0,
 `noindex` tinyint(1) DEFAULT 0,
 `nofollow` tinyint(1) DEFAULT 0,
 `notfound` tinyint(1) DEFAULT 0,
 `hidden_it` tinyint(1) NOT NULL DEFAULT 0,
 `hidden_en` tinyint(1) NOT NULL DEFAULT 0,
 `youtube_video_link_it` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `youtube_video_link_en` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `youtube_video_title_it` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `youtube_video_title_en` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `youtube_video_preview` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `pages_admin_creator_fkey` (`admin_creator`),
 KEY `pages_admin_last_editor_fkey` (`admin_last_editor`),
 CONSTRAINT `pages_admin_creator_fkey` FOREIGN KEY (`admin_creator`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
 CONSTRAINT `pages_admin_last_editor_fkey` FOREIGN KEY (`admin_last_editor`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
pages_tags 	CREATE TABLE `pages_tags` (
 `id_page` int(11) DEFAULT NULL,
 `id_tag` int(11) DEFAULT NULL,
 KEY `id_page_fk` (`id_page`),
 KEY `id_tag_fk` (`id_tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

CREATE TABLE `page_categories` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `sort` int(11) DEFAULT NULL,
 `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
 `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 `title` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `title_it` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `title_en` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `html_it` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `html_en` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `url` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `url_it` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `url_en` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `seo_title_it` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `seo_title_en` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `seo_keywords_it` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `seo_keywords_en` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `seo_tags_it` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `seo_tags_en` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `admin_creator` int(11) DEFAULT NULL,
 `admin_last_editor` int(11) DEFAULT NULL,
 `group_assigned` int(11) DEFAULT NULL,
 `parent` int(11) DEFAULT NULL,
 `template` int(11) DEFAULT NULL,
 `module` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `empty` tinyint(1) DEFAULT 0,
 `noindex` tinyint(1) DEFAULT 0,
 `nofollow` tinyint(1) DEFAULT 0,
 `notfound` tinyint(1) DEFAULT 0,
 `hidden_it` tinyint(1) NOT NULL DEFAULT 0,
 `hidden_en` tinyint(1) NOT NULL DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1005 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

CREATE TABLE `page_templates` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
 `date_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 `file_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `title` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `title_it` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `title_en` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
 `html_header` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `html_footer` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `enabled` tinyint(1) DEFAULT 1,
 `admin_creator` int(11) DEFAULT NULL,
 `admin_last_editor` int(11) DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1003 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

CREATE TABLE `tags` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `tag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `language` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
