SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `am_admin_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `page_url` varchar(200) NOT NULL,
  `controller` varchar(200) NOT NULL,
  `order` int(11) NOT NULL,
  `id_admin_menu` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

INSERT INTO `am_admin_menu` (`id`, `name`, `page_url`, `controller`, `order`, `id_admin_menu`) VALUES(1, 'Escritorio', '/admin/index', '/admin/', 1, 0);

CREATE TABLE IF NOT EXISTS `am_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appcode` varchar(64) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `oauth_return_url` varchar(255) DEFAULT NULL,
  `initialize_app_url` varchar(255) DEFAULT NULL,
  `adduser_url` varchar(255) DEFAULT NULL,
  `deluser_url` varchar(255) DEFAULT NULL,
  `provisionokmail_id` int(11) DEFAULT NULL,
  `version` varchar(8) NOT NULL,
  `cfappframework_id` int(11) NOT NULL,
  `cfappframework_attr` varchar(16) NOT NULL,
  `appfile_path` varchar(256) NOT NULL,
  `appfile_type` varchar(16) NOT NULL,
  `enviroment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `provisioning_log` longtext NOT NULL,
  `provisioningstate` smallint(6) NOT NULL,
  `status` smallint(6) NOT NULL,
  `install_log` text NOT NULL,
  `adduser_log` text NOT NULL,
  `deluser_log` text NOT NULL,
  `client_id` int(11) NOT NULL,
  `cfkey` varchar(64) NOT NULL,
  `manifest` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;


CREATE TABLE IF NOT EXISTS `am_applications_cfservices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `cfservice_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;



CREATE TABLE IF NOT EXISTS `am_cfappframeworks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  `cfcode` varchar(16) NOT NULL,
  `ram` varchar(256) NOT NULL,
  `imgsrc` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

INSERT INTO `am_cfappframeworks` (`id`, `name`, `description`, `cfcode`, `ram`, `imgsrc`) VALUES(1, 'PHP', 'PHP Application', 'php', '128,256,512,1000,2000', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_php.png');
INSERT INTO `am_cfappframeworks` (`id`, `name`, `description`, `cfcode`, `ram`, `imgsrc`) VALUES(2, 'Rails', 'Rails Application', 'rails3', '256,512,1000,2000', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_rubyonrails.png');
INSERT INTO `am_cfappframeworks` (`id`, `name`, `description`, `cfcode`, `ram`, `imgsrc`) VALUES(11, 'Spring', 'Java SpringSource Spring Application', 'spring', '512,1000,2000', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_spring.png');
INSERT INTO `am_cfappframeworks` (`id`, `name`, `description`, `cfcode`, `ram`, `imgsrc`) VALUES(12, 'Grails', 'Java SpringSource Grails Application', 'grails', '512,1000,2000', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_grails.png');
INSERT INTO `am_cfappframeworks` (`id`, `name`, `description`, `cfcode`, `ram`, `imgsrc`) VALUES(15, 'Lift', 'Scala Lift Application', 'lift', '512,1000,2000', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_lift.png');
INSERT INTO `am_cfappframeworks` (`id`, `name`, `description`, `cfcode`, `ram`, `imgsrc`) VALUES(16, 'Java Web', 'Java Web Application', 'java_web', '512,1000,2000', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_java.png');
INSERT INTO `am_cfappframeworks` (`id`, `name`, `description`, `cfcode`, `ram`, `imgsrc`) VALUES(17, 'Sinatra', 'Sinatra Application', 'sinatra', '128,256,512,1000,2000', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_sinatra.png');
INSERT INTO `am_cfappframeworks` (`id`, `name`, `description`, `cfcode`, `ram`, `imgsrc`) VALUES(18, 'Node', 'Node.js Application', 'node', '64,128,256,512,1000,2000', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_nodejs.png');
INSERT INTO `am_cfappframeworks` (`id`, `name`, `description`, `cfcode`, `ram`, `imgsrc`) VALUES(19, 'Django', 'Python Django Application', 'django', '128,256,512,1000,2000', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_django.png');
INSERT INTO `am_cfappframeworks` (`id`, `name`, `description`, `cfcode`, `ram`, `imgsrc`) VALUES(20, 'WSGI', 'Python WSGI Application', 'wsgi', '64,128,256,512,1000,2000', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_wsgi.png');
INSERT INTO `am_cfappframeworks` (`id`, `name`, `description`, `cfcode`, `ram`, `imgsrc`) VALUES(21, 'Erlang/OTP Rebar', 'Erlang/OTP Rebar Application', 'otp_rebar', '64,128,256,512,1000,2000', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_erlang.png');

CREATE TABLE IF NOT EXISTS `am_cfservices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `description` varchar(255) NOT NULL,
  `cfname` varchar(16) NOT NULL,
  `imgurl` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

INSERT INTO `am_cfservices` (`id`, `name`, `description`, `cfname`, `imgurl`) VALUES(1, 'MySQL', 'MySql 5', 'mysql', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_mysql.png');
INSERT INTO `am_cfservices` (`id`, `name`, `description`, `cfname`, `imgurl`) VALUES(2, 'MongoDB', 'Mongo Database', 'mongodb', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_mongodb.png');
INSERT INTO `am_cfservices` (`id`, `name`, `description`, `cfname`, `imgurl`) VALUES(3, 'Redis', 'Redis Server', 'redis', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_redis.png');
INSERT INTO `am_cfservices` (`id`, `name`, `description`, `cfname`, `imgurl`) VALUES(4, 'PostgreSql', 'PostgreSQL Server', 'postgresql', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_postgresql.png');
INSERT INTO `am_cfservices` (`id`, `name`, `description`, `cfname`, `imgurl`) VALUES(5, 'RabbitMq', 'Rabbit MQ ', 'rabbitmq', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_rabbitMQ.png');
INSERT INTO `am_cfservices` (`id`, `name`, `description`, `cfname`, `imgurl`) VALUES(6, 'Neo4j', 'Neo4j NOSQL Store', 'neo4j', 'http://appstore.dev.kactoo.com.ar/admin_template1/images/logo_neo4j.png');

CREATE TABLE IF NOT EXISTS `am_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(256) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

INSERT INTO `am_config` (`id`, `key`, `value`) VALUES(1, 'MAX_APPLICATIONS', '20');
INSERT INTO `am_config` (`id`, `key`, `value`) VALUES(2, 'MAX_APPLICATIONS_RUNNING', '10');

CREATE TABLE IF NOT EXISTS `am_enviroments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `endpoint` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `user` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

INSERT INTO `am_enviroments` (`id`, `endpoint`, `user`, `pass`, `name`, `description`, `created_at`, `user_id`) VALUES(1, 'api.vcap.me', 'andycufari@gmail.com', 'lanacion', 'Local Desarrollo', 'Servidor local de desarrollo', 1349102189, 6);
INSERT INTO `am_enviroments` (`id`, `endpoint`, `user`, `pass`, `name`, `description`, `created_at`, `user_id`) VALUES(2, 'api.blue.enncloud.com', 'egarcia@nozzle.es', 'astragalo', 'Enncloud Blue', 'Enncloud Blue', 1350315309, 6);
INSERT INTO `am_enviroments` (`id`, `endpoint`, `user`, `pass`, `name`, `description`, `created_at`, `user_id`) VALUES(3, 'api.blue.enncloud.com', 'ipenya@nozzle.es', 'gdEj2Npz1zJHc', 'Enncloud Blue Isa', 'Enncloud Blue ISa', 1350915368, 6);
INSERT INTO `am_enviroments` (`id`, `endpoint`, `user`, `pass`, `name`, `description`, `created_at`, `user_id`) VALUES(4, 'api.blue.enncloud.com', 'lucas@ucb', 'ucb', 'Enncloud Blue UCB', 'Enncloud Blue UCB', 1351267567, 6);

CREATE TABLE IF NOT EXISTS `am_provisioned_cfservices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `cfservice_id` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `am_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `user` varchar(64) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `admin_level` tinyint(4) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `max_applications` int(11) NOT NULL DEFAULT '0',
  `max_applications_running` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=10 ;

INSERT INTO `am_users` (`id`, `username`, `user`, `password`, `email`, `activated`, `admin_level`, `created`, `modified`, `max_applications`, `max_applications_running`) VALUES(6, 'GlobalAdmin', 'enncloud-1', '78b66fe4a788003151f98732628930e501523ba6', 'admin@kactoo.com', 1, 1, 1349692402, 1350941224, 0, 0);

