<?php
if(!$create)return;
mysql_query("
CREATE TABLE IF NOT EXISTS `".$db_prefix."shop_categories` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ; ");
mysql_query("
CREATE TABLE IF NOT EXISTS `".$db_prefix."shop_items` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `image` varchar(10000) NOT NULL,
  `price` varchar(10) NOT NULL,
  `views` int(10) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `categorieid` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;");
mysql_query("
CREATE TABLE IF NOT EXISTS `".$db_prefix."shop_useritems` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `userid` int(255) NOT NULL,
  `itemid` int(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=134 ;");
mysql_query("
CREATE TABLE IF NOT EXISTS `".$db_prefix."forum_answers` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `answer` varchar(10000) NOT NULL,
  `userid` int(255) NOT NULL,
  `questionid` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11");
mysql_query("
CREATE TABLE IF NOT EXISTS `".$db_prefix."forum_questions` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `question` varchar(10000) NOT NULL,
  `image` varchar(10000) NOT NULL,
  `userid` int(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;");
mysql_query("
CREATE TABLE IF NOT EXISTS `".$db_prefix."comments` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `userid` int(255) NOT NULL,
  `msg` varchar(5000) COLLATE latin1_german1_ci NOT NULL,
  `timestamp` int(255) NOT NULL,
  `articleid` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ");
mysql_query("
CREATE TABLE IF NOT EXISTS `".$db_prefix."groups` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `edit_articles` varchar(3) COLLATE latin1_german1_ci NOT NULL,
  `edit_comments` varchar(3) COLLATE latin1_german1_ci NOT NULL,
  `edit_user` varchar(3) COLLATE latin1_german1_ci NOT NULL,
  `edit_pages` varchar(3) COLLATE latin1_german1_ci NOT NULL,
  `edit_groups` varchar(3) COLLATE latin1_german1_ci NOT NULL,
  `edit_newsletter` varchar(3) COLLATE latin1_german1_ci NOT NULL,
  `edit_prefs` varchar(3) COLLATE latin1_german1_ci NOT NULL,
  `edit_forum` varchar(3) COLLATE latin1_german1_ci NOT NULL,
  `edit_shop` varchar(3) COLLATE latin1_german1_ci NOT NULL,
  `description` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=10 ;");
mysql_query("
CREATE TABLE IF NOT EXISTS `".$db_prefix."pages` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `orderid` int(255) NOT NULL,
  `title` varchar(500) COLLATE latin1_german1_ci NOT NULL,
  `description` varchar(55000) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ");
mysql_query("
CREATE TABLE IF NOT EXISTS `".$db_prefix."preferences` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `value` varchar(50000) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ");
mysql_query("
CREATE TABLE IF NOT EXISTS `".$db_prefix."user` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `username` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `email` varchar(500) COLLATE latin1_german1_ci NOT NULL,
  `password` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `groupid` int(255) NOT NULL,
  `deactive` varchar(3) COLLATE latin1_german1_ci NOT NULL DEFAULT 'no',
  `timestamp` int(255) NOT NULL,
  `last_login` int(255) NOT NULL,
  `icon` varchar(5000) COLLATE latin1_german1_ci NOT NULL,
  `newsletter` varchar(3) COLLATE latin1_german1_ci NOT NULL DEFAULT 'yes',
  `md5` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `profilepic` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ");
if($_SESSION['create_placeholders']=="yes") {
if($_SESSION['l']=="de") {
mysql_query("
INSERT INTO `".$db_prefix."user` (`id`, `name`, `username`, `email`, `password`, `groupid`, `deactive`, `timestamp`, `last_login`, `icon`, `newsletter`, `md5`) VALUES
(2, 'Rainer Zufall', 'rainer', 'rainer.zufall@irgendwas.de', 'cc03e747a6afbbcbf8be7668acfebee5', 0, '', ".time().", 0, '', 'no', '4f139d783b8dfd26098080d29e3ce968'),
(3, 'Max Mustermann', 'max', 'max.musterman@domain.com', 'cc03e747a6afbbcbf8be7668acfebee5', 0, '', ".time().", 0, '', 'no', '827b5b8d3d728beac6f162109711888f');
");
mysql_query("
INSERT INTO `".$db_prefix."pages` (`id`, `title`, `description`) VALUES
(1, 'Pinguine', 'Ein paar Beitr&auml;ge &uuml;ber Pinguine'),
(2, 'Affen', 'Informationen &uuml;ber Affen');");
mysql_query("
INSERT INTO `".$db_prefix."articles` (`id`, `pageid`, `title`, `code`, `editor`, `timestamp`, `public`, `comments`, `views`) VALUES
(4, 1, 'Groesse und Gewicht', '<img src=\"http://upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Falkland_Islands_Penguins_36.jpg/300px-Falkland_Islands_Penguins_36.jpg\" style=\"float:left;margin:5px 20px 10px 5px;\">Der Zwergpinguin (Eudyptula minor) erreicht lediglich eine Gr&ouml;sse von 30 Zentimetern und ein Gewicht von einem bis eineinhalb Kilogramm, dagegen geh&ouml;rt der Kaiserpinguin (Aptenodytes forsteri) mit einer Gr&ouml;sse von bis zu 1,20 Metern und einem Gewicht von bis zu 40 Kilogramm zu den gr&ouml;ssten Neukieferv&ouml;geln &uuml;berhaupt. Dieser Gr&ouml;ssenunterschied wird durch die Bergmannsche Regel erkl&auml;rt, f&uuml;r welche die Pinguine ein h&auml;ufig angef&uuml;hrtes Beispiel sind. Die Bergmannsche Regel besagt, dass Tiere in k&auml;lteren Regionen gr&ouml;sser sind, da dies zu einem g&uuml;nstigerem Verh&auml;ltnis von Volumen zu Oberfl&auml;che des Tieres und damit zu weniger W&auml;rmeverlust f&uuml;hrt. Die meisten Arten sind nur um weniges leichter als das von ihnen verdr&auml;ngte Wasser, so dass ihnen das Tauchen vergleichsweise leicht f&auml;llt.\r\n', 'COMIS', ".time().", 'yes', 'yes', 78),
(3, 1, 'W&auml;rmeregulation', 'Pinguine sind in ihrem Lebensraum zum Teil extremen klimatischen Bedingungen ausgesetzt und haben sich daran durch verschiedene anatomische Merkmale angepasst.\r\n\r\nZur W&auml;rmeisolation dient zun&auml;chst eine ausgepr&auml;gte, oft zwei bis drei Zentimeter dicke Fettschicht, &uuml;ber der sich drei wasserdichte Schichten kurzer, dicht gepackter und gleichm&auml;ssig &uuml;ber den ganzen K&ouml;rper verteilter Federn befinden. Apterien, Hautregionen, in denen keine Federn wachsen, gibt es bei Pinguinen im Gegensatz zu fast allen anderen V&ouml;geln nicht; eine Ausnahme bildet bei manchen tropischen Arten die Gesichtshaut. Die in den Federschichten eingeschlossene Luft sch&uuml;tzt im Wasser ebenfalls sehr effektiv vor W&auml;rmeverlusten.\r\n\r\nDaneben besitzen Pinguine hoch entwickelte W&auml;rme&uuml;bertrager in ihren Flossen und Beinen: Das in diese Gliedmassen einstr&ouml;mende arterielle Blut gibt seine W&auml;rme zu einem grossen Teil an das k&uuml;hlere in den K&ouml;rper zur&uuml;ckstr&ouml;mende ven&ouml;se Blut ab, so dass W&auml;rmeverluste minimiert werden. Dies wird als Gegenstromprinzip bezeichnet.\r\n\r\nAuf der anderen Seite k&auml;mpfen einige in tropischen Gew&auml;ssern beheimatete Pinguinarten eher mit &Uuml;berhitzung. Um dies zu verhindern, sind ihre Flossen im Vergleich zur K&ouml;rpergr&ouml;sse verbreitert, so dass die Fl&auml;che, &uuml;ber die W&auml;rme abgegeben werden kann, erweitert ist. Bei einigen Arten ist zudem die Gesichtshaut nicht von Federn bedeckt, so dass aufgestaute W&auml;rme im aktiv aufgesuchten Schatten schneller abgegeben werden kann. Manche Pinguinarten verlagern ihre Aktivit&auml;tszeit sogar vollst&auml;ndig auf den Abend oder die Nacht.', 'COMIS', ". time().", 'yes', 'yes', 19),
(2, 2, 'Lebensweise', 'Affen sind mit Ausnahme der Nachtaffen immer tagaktiv. Sie haben verschiedene Fortbewegungsarten entwickelt, neben dem zweibeinigen Gehen (Mensch) und dem vierbeinigen Gehen findet sich auch das senkrechte Klettern und Springen und das Schwinghangeln. Die Mehrzahl der Affenarten sind vorwiegend oder reine Baumbewohner.\r\n\r\nAffen haben in den meisten F&auml;llen ein komplexes Sozialverhalten entwickelt, Einzelg&auml;nger sind selten. Manche Arten bilden grosse gemischte Gruppen, andere leben in Haremsgruppen, in denen ein einzelnes M&auml;nnchen zahlreiche Weibchen um sich schart, wieder andere leben in langj&auml;hrigen monogamen Beziehungen. In Gruppen bildet sich oft eine Rangordnung heraus, die durch K&auml;mpfe, Alter, Verwandtschaft und andere Faktoren bestimmt ist.\r\n', 'COMIS', 1411876635, 'yes', 'yes', 78),
(1, 2, 'Verbreitung und Lebensraum', '<img src=\"http://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Abuko_monkey.jpg/300px-Abuko_monkey.jpg\" style=\"float:right;margin:5px 5px 10px 27px;\">Die heute mit Abstand individuenreichste Affenart ist der Mensch mit einer weltweiten Population von mehr als 7 Milliarden. Er hat alle Kontinente mit Ausnahme von Antarktika besiedelt und ist auch weltweit das S&auml;ugetier mit der gr&ouml;ssten Population.\r\n\r\nAffen mit Ausnahme des Menschen sind in den tropischen und subtropischen Regionen Amerikas, Afrikas und Asiens verbreitet. In Amerika reicht ihr Verbreitungsgebiet vom s&uuml;dlichen Mexiko bis ins n&ouml;rdliche Argentinien. In Afrika sind sie weitverbreitet, die gr&ouml;sste Artendichte gibt es s&uuml;dlich der Sahara. Auf Madagaskar gibt es keine Affen ausser dem Menschen, hier sind die Primaten ansonsten nur durch die Lemuren vertreten. In Asien sind sie vorwiegend in S&uuml;d- und S&uuml;dostasien vertreten, ihr Verbreitungsgebiet reicht bis Japan beziehungsweise Timor. Die einzige in Europa freilebende Affenart ausser dem Menschen ist der Berberaffe auf Gibraltar, diese Population wurde aber vermutlich vom Menschen eingef&uuml;hrt.\r\n\r\nDer Lebensraum der Affen mit Ausnahme des Menschen sind vorwiegend W&auml;lder und andere baumbestandene Gebiete. Sie sind dabei in verschiedensten Waldtypen zu finden, von Regenw&auml;ldern bis in Gebirgsw&auml;lder &uuml;ber 3000 Meter H&ouml;he. Einige Arten sind teilweise Bodenbewohner, am ausgepr&auml;gtesten der Dschelada.\r\n', 'COMIS', ".time().", 'yes', 'yes', 24);
");
mysql_query("
INSERT INTO `".$db_prefix."groups` (`id`, `name`, `edit_articles`, `edit_comments`, `edit_user`, `edit_pages`, `edit_groups`, `edit_newsletter`, `edit_prefs`, `edit_forum`, `edit_shop`, `description`) VALUES
(1, 'Administratoren', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'D&uuml;rfen alles'),
(2, 'Moderatoren', 'yes', 'yes', 'no', 'yes', 'no', 'no', 'no', 'yes', 'yes', 'Sind nur f&uuml;r Inhalte zust&auml;ndig'),
(3, 'Verwalter', 'no', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'no', 'K&uuml;mmern sich um Einstellungen und User'),
(4, 'Besucher', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'Hat nix im Admin Center zu suchen');");
}
else {
mysql_query("
INSERT INTO `".$db_prefix."user` (`id`, `name`, `username`, `email`, `password`, `groupid`, `deactive`, `timestamp`, `last_login`, `icon`, `newsletter`, `md5`) VALUES
(2, 'John Miller', 'john', 'johnny@something.com', 'cc03e747a6afbbcbf8be7668acfebee5', 0, '', ".time().", 0, '', 'no', '4f139d783b8dfd26098080d29e3ce968'),
(3, 'David Smith', 'david', 'david.smith@domain.com', 'cc03e747a6afbbcbf8be7668acfebee5', 0, '', ".time().", 0, '', 'no', '827b5b8d3d728beac6f162109711888f');
");
mysql_query("
INSERT INTO `".$db_prefix."pages` (`id`, `title`, `description`) VALUES
(1, 'Pinguins', 'This is Test Content'),
(2, 'Monkeys', 'Informations about Monkeys');");
mysql_query("
INSERT INTO `".$db_prefix."articles` (`id`, `pageid`, `title`, `code`, `editor`, `timestamp`, `public`, `comments`, `views`) VALUES
(1, 1, 'Penguins and humans', '<img src=\"http://upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Falkland_Islands_Penguins_36.jpg/300px-Falkland_Islands_Penguins_36.jpg\" style=\"float:left;margin:5px 20px 10px 5px;\">Penguins seem to have no special fear of humans, and have approached groups of explorers without hesitation. This is probably because penguins have no land predators in Antarctica or the nearby offshore islands. Dogs preyed upon penguins while they were allowed in Antarctica during the age of early human exploration as sled dogs, but dogs are now banned from Antarctica.[citation needed] Instead, adult penguins are at risk at sea from predators such as sharks, the orca, and the leopard seal. Typically, penguins do not approach closer than about 3 meters (9.8 feet) at which point they become nervous. This is also the distance that Antarctic tourists are told to keep from penguins (tourists are not supposed to approach closer than 3 meters, but are not expected to withdraw if the penguins come closer).\n\nIn June 2011, a penguin came ashore on New Zealands Peka Peka Beach, 3200 km off course on its journey to Antarctica. Nicknamed Happy Feet, after the movie of the same name, it was suffering from heat exhaustion and had to undergo a number of operations to remove objects like driftwood and sand from its stomach. Happy Feet was a media sensation, with extensive coverage on TV and the web, including a live stream that had thousands of views and a visit from English actor Stephen Fry.\n\nOnce he had recovered, Happy Feet was released back into the water south of New Zealand.', 'admin', 1411876623, 'yes', 'yes', 0),
(2, 1, 'In popular culture', 'Penguins are popular around the world, primarily for their unusually upright, waddling gait and (compared to other birds) lack of fear of humans. Their striking black-and-white plumage is often likened to a white tie suit. Mistakenly, some artists and writers have penguins based at the North Pole. This is incorrect, as there are almost no wild penguins in the Northern Hemisphere, except the small group on the northernmost of the Galapagos. The cartoon series Chilly Willy helped perpetuate this myth, as the title penguin would interact with northern-hemisphere species, such as polar bears and walruses.', 'admin', ".time().", 'yes', 'yes', 0),
(3, 2, 'Religion', 'Hanuman, a prominent divine entity in Hinduism, is a Human-like monkey god. He bestows courage, strength and longevity to the person who thinks about him or the god Rama.\n\nIn Buddhism, the monkey is an early incarnation of Buddha but may also represent trickery and ugliness. The Chinese Buddhist mind monkey metaphor refers to the unsettled, restless state of human mind. Monkey is also one of the Three Senseless Creatures, symbolizing greed, with the tiger representing anger and the deer lovesickness.\n\nThe Mizaru, or three wise monkeys, are revered in Japanese folklore, together they embody the proverbial principle to see no evil, hear no evil, speak no evil.\n\nThe Moche people of ancient Peru worshipped nature. They placed emphasis on animals and often depicted monkeys in their art.\n\nThe Tzeltal people of Mexico worshipped monkeys as incarnations of their dead ancestors.\n', 'admin', ".time().", 'yes', 'yes', 78),
(4, 2, 'Physical description', '<img src=\"http://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Abuko_monkey.jpg/300px-Abuko_monkey.jpg\" style=\"float:right;margin:5px 5px 10px 27px\">Monkeys range in size from the pygmy marmoset, which can be as small as 117 millimetres (4.6 in) with a 172-millimetre (6.8 in) tail and just over 100 grams (3.5 oz) in weight, to the male mandrill, almost 1 metre (3.3 ft) long and weighing up to 36 kilograms (79 lb). Some are arboreal (living in trees) while others live on the savanna; diets differ among the various species but may contain any of the following: fruit, leaves, seeds, nuts, flowers, eggs and small animals (including insects and spiders).\n\nSome characteristics are shared among the groups; most New World monkeys have prehensile tails while Old World monkeys have non-prehensile tails or no visible tail at all. Old World monkeys have trichromatic color vision like that of humans, while New World monkeys may be trichromatic, dichromatic, or—as in the owl monkeys and greater galagos—monochromatic. Although both the New and Old World monkeys, like the apes, have forward-facing eyes, the faces of Old World and New World monkeys look very different, though again, each group shares some features such as the types of noses, cheeks and rumps.', 'admin', ".time().", 'yes', 'yes', 0);
");
mysql_query("
INSERT INTO `".$db_prefix."groups` (`id`, `name`, `edit_articles`, `edit_comments`, `edit_user`, `edit_pages`, `edit_groups`, `edit_newsletter`, `edit_prefs`,  `edit_forum`,  `edit_shop`, `description`) VALUES
(1, 'Superadmins', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'Are allowed to do all'),
(2, 'Moderators', 'yes', 'yes', 'no', 'yes', 'no', 'no', 'no', 'yes', 'yes', 'responsible for Content'),
(3, 'Manager', 'no', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'no', 'responsible for Preferences and Users'),
(4, 'Visitors', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no access to the Admin Center');");
}
}
