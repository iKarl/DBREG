CREATE TABLE `smc_catPaises` (
  `pais_idPais` smallint(5) NOT NULL AUTO_INCREMENT,
  `pais_isoNum` smallint(5) NOT NULL,
  `pais_iso2` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `pais_iso3` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `pais_nombreEs` varchar(90) COLLATE utf8_unicode_ci NOT NULL,
  `pais_nombreEn` varchar(90) COLLATE utf8_unicode_ci DEFAULT '',
  `pais_imagen` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pais_fechaModificacion` datetime DEFAULT NULL,
  `pais_usuarioModifico` int(11) DEFAULT NULL,
  PRIMARY KEY (`pais_idPais`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Contiene los paises del mundo' AUTO_INCREMENT=241 ;

--
-- Volcar la base de datos para la tabla `smc_catPaises`
--

INSERT INTO `smc_catPaises` VALUES(1, 4, 'AF', 'AFG', 'Afganistán', '', 'af.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(2, 248, 'AX', 'ALA', 'Islas Gland', '', 'ax.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(3, 8, 'AL', 'ALB', 'Albania', '', 'al.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(4, 276, 'DE', 'DEU', 'Alemania', '', 'de.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(5, 20, 'AD', 'AND', 'Andorra', '', 'ad.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(6, 24, 'AO', 'AGO', 'Angola', '', 'ao.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(7, 660, 'AI', 'AIA', 'Anguilla', '', 'ai.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(8, 10, 'AQ', 'ATA', 'Antártida', '', 'aq.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(9, 28, 'AG', 'ATG', 'Antigua y Barbuda', '', 'ag.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(10, 530, 'AN', 'ANT', 'Antillas Holandesas', '', 'an.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(11, 682, 'SA', 'SAU', 'Arabia Saudí', '', 'sa.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(12, 12, 'DZ', 'DZA', 'Argelia', '', 'dz.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(13, 32, 'AR', 'ARG', 'Argentina', '', 'ar.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(14, 51, 'AM', 'ARM', 'Armenia', '', 'am.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(15, 533, 'AW', 'ABW', 'Aruba', '', 'aw.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(16, 36, 'AU', 'AUS', 'Australia', '', 'au.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(17, 40, 'AT', 'AUT', 'Austria', '', 'at.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(18, 31, 'AZ', 'AZE', 'Azerbaiyán', '', 'az.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(19, 44, 'BS', 'BHS', 'Bahamas', '', 'bs.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(20, 48, 'BH', 'BHR', 'Bahréin', '', 'bh.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(21, 50, 'BD', 'BGD', 'Bangladesh', '', 'bd.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(22, 52, 'BB', 'BRB', 'Barbados', '', 'bb.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(23, 112, 'BY', 'BLR', 'Bielorrusia', '', 'by.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(24, 56, 'BE', 'BEL', 'Bélgica', '', 'be.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(25, 84, 'BZ', 'BLZ', 'Belice', '', 'bz.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(26, 204, 'BJ', 'BEN', 'Benin', '', 'bj.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(27, 60, 'BM', 'BMU', 'Bermudas', '', 'bm.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(28, 64, 'BT', 'BTN', 'Bhután', '', 'bt.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(29, 68, 'BO', 'BOL', 'Bolivia', '', 'bo.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(30, 70, 'BA', 'BIH', 'Bosnia y Herzegovina', '', 'ba.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(31, 72, 'BW', 'BWA', 'Botsuana', '', 'bw.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(32, 74, 'BV', 'BVT', 'Isla Bouvet', '', 'bv.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(33, 76, 'BR', 'BRA', 'Brasil', '', 'br.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(34, 96, 'BN', 'BRN', 'Brunéi', '', 'bn.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(35, 100, 'BG', 'BGR', 'Bulgaria', '', 'bg.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(36, 854, 'BF', 'BFA', 'Burkina Faso', '', 'bf.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(37, 108, 'BI', 'BDI', 'Burundi', '', 'bi.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(38, 132, 'CV', 'CPV', 'Cabo Verde', '', 'cv.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(39, 136, 'KY', 'CYM', 'Islas Caimán', '', 'ky.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(40, 116, 'KH', 'KHM', 'Camboya', '', 'kh.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(41, 120, 'CM', 'CMR', 'Camerún', '', 'cm.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(42, 124, 'CA', 'CAN', 'Canadá', '', 'ca.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(43, 140, 'CF', 'CAF', 'República Centroafricana', '', 'cf.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(44, 148, 'TD', 'TCD', 'Chad', '', 'td.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(45, 203, 'CZ', 'CZE', 'República Checa', '', 'cz.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(46, 152, 'CL', 'CHL', 'Chile', '', 'cl.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(47, 156, 'CN', 'CHN', 'China', '', 'cn.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(48, 196, 'CY', 'CYP', 'Chipre', '', 'cy.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(49, 162, 'CX', 'CXR', 'Isla de Navidad', '', 'cx.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(50, 336, 'VA', 'VAT', 'Ciudad del Vaticano', '', 'va.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(51, 166, 'CC', 'CCK', 'Islas Cocos', '', 'cc.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(52, 170, 'CO', 'COL', 'Colombia', '', 'co.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(53, 174, 'KM', 'COM', 'Comoras', '', 'km.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(54, 180, 'CD', 'COD', 'República Democrática del Congo', '', 'cd.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(55, 178, 'CG', 'COG', 'Congo', '', 'cg.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(56, 184, 'CK', 'COK', 'Islas Cook', '', 'ck.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(57, 408, 'KP', 'PRK', 'Corea del Norte', '', 'kp.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(58, 410, 'KR', 'KOR', 'Corea del Sur', '', 'kr.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(59, 384, 'CI', 'CIV', 'Costa de Marfil', '', 'ci.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(60, 188, 'CR', 'CRI', 'Costa Rica', '', 'cr.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(61, 191, 'HR', 'HRV', 'Croacia', '', 'hr.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(62, 192, 'CU', 'CUB', 'Cuba', '', 'cu.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(63, 208, 'DK', 'DNK', 'Dinamarca', '', 'dk.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(64, 212, 'DM', 'DMA', 'Dominica', '', 'dm.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(65, 214, 'DO', 'DOM', 'República Dominicana', '', 'do.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(66, 218, 'EC', 'ECU', 'Ecuador', '', 'ec.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(67, 818, 'EG', 'EGY', 'Egipto', '', 'eg.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(68, 222, 'SV', 'SLV', 'El Salvador', '', 'sv.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(69, 784, 'AE', 'ARE', 'Emiratos Árabes Unidos', '', 'ae.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(70, 232, 'ER', 'ERI', 'Eritrea', '', 'er.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(71, 703, 'SK', 'SVK', 'Eslovaquia', '', 'sk.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(72, 705, 'SI', 'SVN', 'Eslovenia', '', 'si.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(73, 724, 'ES', 'ESP', 'España', '', 'es.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(74, 581, 'UM', 'UMI', 'Islas ultramarinas de Estados Unidos', '', 'um.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(75, 840, 'US', 'USA', 'Estados Unidos', '', 'us.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(76, 233, 'EE', 'EST', 'Estonia', '', 'ee.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(77, 231, 'ET', 'ETH', 'Etiopía', '', 'et.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(78, 234, 'FO', 'FRO', 'Islas Feroe', '', 'fo.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(79, 608, 'PH', 'PHL', 'Filipinas', '', 'ph.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(80, 246, 'FI', 'FIN', 'Finlandia', '', 'fi.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(81, 242, 'FJ', 'FJI', 'Fiyi', '', 'fj.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(82, 250, 'FR', 'FRA', 'Francia', '', 'fr.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(83, 266, 'GA', 'GAB', 'Gabón', '', 'ga.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(84, 270, 'GM', 'GMB', 'Gambia', '', 'gm.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(85, 268, 'GE', 'GEO', 'Georgia', '', 'ge.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(86, 239, 'GS', 'SGS', 'Islas Georgias del Sur y Sandwich del Sur', '', 'gs.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(87, 288, 'GH', 'GHA', 'Ghana', '', 'gh.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(88, 292, 'GI', 'GIB', 'Gibraltar', '', 'gi.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(89, 308, 'GD', 'GRD', 'Granada', '', 'gd.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(90, 300, 'GR', 'GRC', 'Grecia', '', 'gr.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(91, 304, 'GL', 'GRL', 'Groenlandia', '', 'gl.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(92, 312, 'GP', 'GLP', 'Guadalupe', '', 'gp.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(93, 316, 'GU', 'GUM', 'Guam', '', 'gu.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(94, 320, 'GT', 'GTM', 'Guatemala', '', 'gt.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(95, 254, 'GF', 'GUF', 'Guayana Francesa', '', 'gf.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(96, 324, 'GN', 'GIN', 'Guinea', '', 'gn.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(97, 226, 'GQ', 'GNQ', 'Guinea Ecuatorial', '', 'gq.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(98, 624, 'GW', 'GNB', 'Guinea-Bissau', '', 'gw.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(99, 328, 'GY', 'GUY', 'Guyana', '', 'gy.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(100, 332, 'HT', 'HTI', 'Haití', '', 'ht.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(101, 334, 'HM', 'HMD', 'Islas Heard y McDonald', '', 'hm.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(102, 340, 'HN', 'HND', 'Honduras', '', 'hn.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(103, 344, 'HK', 'HKG', 'Hong Kong', '', 'hk.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(104, 348, 'HU', 'HUN', 'Hungría', '', 'hu.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(105, 356, 'IN', 'IND', 'India', '', 'in.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(106, 360, 'ID', 'IDN', 'Indonesia', '', 'id.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(107, 364, 'IR', 'IRN', 'Irán', '', 'ir.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(108, 368, 'IQ', 'IRQ', 'Iraq', '', 'iq.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(109, 372, 'IE', 'IRL', 'Irlanda', '', 'ie.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(110, 352, 'IS', 'ISL', 'Islandia', '', 'is.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(111, 376, 'IL', 'ISR', 'Israel', '', 'il.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(112, 380, 'IT', 'ITA', 'Italia', '', 'it.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(113, 388, 'JM', 'JAM', 'Jamaica', '', 'jm.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(114, 392, 'JP', 'JPN', 'Japón', '', 'jp.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(115, 400, 'JO', 'JOR', 'Jordania', '', 'jo.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(116, 398, 'KZ', 'KAZ', 'Kazajstán', '', 'kz.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(117, 404, 'KE', 'KEN', 'Kenia', '', 'ke.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(118, 417, 'KG', 'KGZ', 'Kirguistán', '', 'kg.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(119, 296, 'KI', 'KIR', 'Kiribati', '', 'ki.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(120, 414, 'KW', 'KWT', 'Kuwait', '', 'kw.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(121, 418, 'LA', 'LAO', 'Laos', '', 'la.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(122, 426, 'LS', 'LSO', 'Lesotho', '', 'ls.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(123, 428, 'LV', 'LVA', 'Letonia', '', 'lv.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(124, 422, 'LB', 'LBN', 'Líbano', '', 'lb.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(125, 430, 'LR', 'LBR', 'Liberia', '', 'lr.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(126, 434, 'LY', 'LBY', 'Libia', '', 'ly.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(127, 438, 'LI', 'LIE', 'Liechtenstein', '', 'li.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(128, 440, 'LT', 'LTU', 'Lituania', '', 'lt.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(129, 442, 'LU', 'LUX', 'Luxemburgo', '', 'lu.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(130, 446, 'MO', 'MAC', 'Macao', '', 'mo.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(131, 807, 'MK', 'MKD', 'ARY Macedonia', '', 'mk.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(132, 450, 'MG', 'MDG', 'Madagascar', '', 'mg.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(133, 458, 'MY', 'MYS', 'Malasia', '', 'my.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(134, 454, 'MW', 'MWI', 'Malawi', '', 'mw.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(135, 462, 'MV', 'MDV', 'Maldivas', '', 'mv.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(136, 466, 'ML', 'MLI', 'Malí', '', 'ml.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(137, 470, 'MT', 'MLT', 'Malta', '', 'mt.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(138, 238, 'FK', 'FLK', 'Islas Malvinas', '', 'fk.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(139, 580, 'MP', 'MNP', 'Islas Marianas del Norte', '', 'mp.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(140, 504, 'MA', 'MAR', 'Marruecos', '', 'ma.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(141, 584, 'MH', 'MHL', 'Islas Marshall', '', 'mh.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(142, 474, 'MQ', 'MTQ', 'Martinica', '', 'mq.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(143, 480, 'MU', 'MUS', 'Mauricio', '', 'mu.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(144, 478, 'MR', 'MRT', 'Mauritania', '', 'mr.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(145, 175, 'YT', 'MYT', 'Mayotte', '', 'yt.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(146, 484, 'MX', 'MEX', 'México', '', 'mx.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(147, 583, 'FM', 'FSM', 'Micronesia', '', 'fm.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(148, 498, 'MD', 'MDA', 'Moldavia', '', 'md.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(149, 492, 'MC', 'MCO', 'Mónaco', '', 'mc.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(150, 496, 'MN', 'MNG', 'Mongolia', '', 'mn.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(151, 500, 'MS', 'MSR', 'Montserrat', '', 'ms.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(152, 508, 'MZ', 'MOZ', 'Mozambique', '', 'mz.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(153, 104, 'MM', 'MMR', 'Myanmar', '', 'mm.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(154, 516, 'NA', 'NAM', 'Namibia', '', 'na.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(155, 520, 'NR', 'NRU', 'Nauru', '', 'nr.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(156, 524, 'NP', 'NPL', 'Nepal', '', 'np.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(157, 558, 'NI', 'NIC', 'Nicaragua', '', 'ni.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(158, 562, 'NE', 'NER', 'Níger', '', 'ne.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(159, 566, 'NG', 'NGA', 'Nigeria', '', 'ng.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(160, 570, 'NU', 'NIU', 'Niue', '', 'nu.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(161, 574, 'NF', 'NFK', 'Isla Norfolk', '', 'nf.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(162, 578, 'NO', 'NOR', 'Noruega', '', 'no.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(163, 540, 'NC', 'NCL', 'Nueva Caledonia', '', 'nc.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(164, 554, 'NZ', 'NZL', 'Nueva Zelanda', '', 'nz.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(165, 512, 'OM', 'OMN', 'Omán', '', 'om.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(166, 528, 'NL', 'NLD', 'Países Bajos', '', 'nl.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(167, 586, 'PK', 'PAK', 'Pakistán', '', 'pk.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(168, 585, 'PW', 'PLW', 'Palau', '', 'pw.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(169, 275, 'PS', 'PSE', 'Palestina', '', 'ps.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(170, 591, 'PA', 'PAN', 'Panamá', '', 'pa.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(171, 598, 'PG', 'PNG', 'Papúa Nueva Guinea', '', 'pg.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(172, 600, 'PY', 'PRY', 'Paraguay', '', 'py.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(173, 604, 'PE', 'PER', 'Perú', '', 'pe.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(174, 612, 'PN', 'PCN', 'Islas Pitcairn', '', 'pn.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(175, 258, 'PF', 'PYF', 'Polinesia Francesa', '', 'pf.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(176, 616, 'PL', 'POL', 'Polonia', '', 'pl.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(177, 620, 'PT', 'PRT', 'Portugal', '', 'pt.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(178, 630, 'PR', 'PRI', 'Puerto Rico', '', 'pr.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(179, 634, 'QA', 'QAT', 'Qatar', '', 'qa.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(180, 826, 'GB', 'GBR', 'Reino Unido', '', 'gb.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(181, 638, 'RE', 'REU', 'Reunión', '', 're.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(182, 646, 'RW', 'RWA', 'Ruanda', '', 'rw.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(183, 642, 'RO', 'ROU', 'Rumania', '', 'ro.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(184, 643, 'RU', 'RUS', 'Rusia', '', 'ru.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(185, 732, 'EH', 'ESH', 'Sahara Occidental', '', 'eh.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(186, 90, 'SB', 'SLB', 'Islas Salomón', '', 'sb.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(187, 882, 'WS', 'WSM', 'Samoa', '', 'ws.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(188, 16, 'AS', 'ASM', 'Samoa Americana', '', 'as.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(189, 659, 'KN', 'KNA', 'San Cristóbal y Nevis', '', 'kn.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(190, 674, 'SM', 'SMR', 'San Marino', '', 'sm.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(191, 666, 'PM', 'SPM', 'San Pedro y Miquelón', '', 'pm.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(192, 670, 'VC', 'VCT', 'San Vicente y las Granadinas', '', 'vc.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(193, 654, 'SH', 'SHN', 'Santa Helena', '', 'sh.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(194, 662, 'LC', 'LCA', 'Santa Lucía', '', 'lc.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(195, 678, 'ST', 'STP', 'Santo Tomé y Príncipe', '', 'st.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(196, 686, 'SN', 'SEN', 'Senegal', '', 'sn.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(197, 891, 'CS', 'SCG', 'Serbia y Montenegro', '', 'cs.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(198, 690, 'SC', 'SYC', 'Seychelles', '', 'sc.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(199, 694, 'SL', 'SLE', 'Sierra Leona', '', 'sl.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(200, 702, 'SG', 'SGP', 'Singapur', '', 'sg.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(201, 760, 'SY', 'SYR', 'Siria', '', 'sy.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(202, 706, 'SO', 'SOM', 'Somalia', '', 'so.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(203, 144, 'LK', 'LKA', 'Sri Lanka', '', 'lk.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(204, 748, 'SZ', 'SWZ', 'Suazilandia', '', 'sz.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(205, 710, 'ZA', 'ZAF', 'Sudáfrica', '', 'za.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(206, 736, 'SD', 'SDN', 'Sudán', '', 'sd.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(207, 752, 'SE', 'SWE', 'Suecia', '', 'se.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(208, 756, 'CH', 'CHE', 'Suiza', '', 'ch.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(209, 740, 'SR', 'SUR', 'Surinam', '', 'sr.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(210, 744, 'SJ', 'SJM', 'Svalbard y Jan Mayen', '', 'sj.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(211, 764, 'TH', 'THA', 'Tailandia', '', 'th.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(212, 158, 'TW', 'TWN', 'Taiwán', '', 'tw.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(213, 834, 'TZ', 'TZA', 'Tanzania', '', 'tz.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(214, 762, 'TJ', 'TJK', 'Tayikistán', '', 'tj.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(215, 86, 'IO', 'IOT', 'Territorio Británico del Océano Índico', '', 'io.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(216, 260, 'TF', 'ATF', 'Territorios Australes Franceses', '', 'tf.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(217, 626, 'TL', 'TLS', 'Timor Oriental', '', 'tl.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(218, 768, 'TG', 'TGO', 'Togo', '', 'tg.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(219, 772, 'TK', 'TKL', 'Tokelau', '', 'tk.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(220, 776, 'TO', 'TON', 'Tonga', '', 'to.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(221, 780, 'TT', 'TTO', 'Trinidad y Tobago', '', 'tt.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(222, 788, 'TN', 'TUN', 'Túnez', '', 'tn.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(223, 796, 'TC', 'TCA', 'Islas Turcas y Caicos', '', 'tc.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(224, 795, 'TM', 'TKM', 'Turkmenistán', '', 'tm.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(225, 792, 'TR', 'TUR', 'Turquía', '', 'tr.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(226, 798, 'TV', 'TUV', 'Tuvalu', '', 'tv.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(227, 804, 'UA', 'UKR', 'Ucrania', '', 'ua.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(228, 800, 'UG', 'UGA', 'Uganda', '', 'ug.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(229, 858, 'UY', 'URY', 'Uruguay', '', 'uy.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(230, 860, 'UZ', 'UZB', 'Uzbekistán', '', 'uz.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(231, 548, 'VU', 'VUT', 'Vanuatu', '', 'vu.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(232, 862, 'VE', 'VEN', 'Venezuela', '', 've.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(233, 704, 'VN', 'VNM', 'Vietnam', '', 'vn.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(234, 92, 'VG', 'VGB', 'Islas Vírgenes Británicas', '', 'vg.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(235, 850, 'VI', 'VIR', 'Islas Vírgenes de los Estados Unidos', '', 'vi.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(236, 876, 'WF', 'WLF', 'Wallis y Futuna', '', 'wf.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(237, 887, 'YE', 'YEM', 'Yemen', '', 'ye.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(238, 262, 'DJ', 'DJI', 'Yibuti', '', 'dj.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(239, 894, 'ZM', 'ZMB', 'Zambia', '', 'zm.png', '0000-00-00 00:00:00', 0);
INSERT INTO `smc_catPaises` VALUES(240, 716, 'ZW', 'ZWE', 'Zimbabue', '', 'zw.png', '0000-00-00 00:00:00', 0);