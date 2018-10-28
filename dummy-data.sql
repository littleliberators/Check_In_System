/* Delete the tables if they already exist */
drop table if exists Parent;
drop table if exists Child;

/* Create the schema for our tables */
create table Child(child_id int, first_name varchar(50), last_name varchar(50), age int);
create table Parent(parent_id int, first_name varchar(50), last_name varchar(50), phone_num varchar(10), email varchar(50), relationship varchar(50), PIN int);

/* Populate the tables with our data */
/* Child */
insert into Child (child_id, first_name, last_name, age) values (1, 'Miguela', 'Barrowclough', 0);
insert into Child (child_id, first_name, last_name, age) values (2, 'Maridel', 'Bolger', 0);
insert into Child (child_id, first_name, last_name, age) values (3, 'Phaidra', 'Pes', 4);
insert into Child (child_id, first_name, last_name, age) values (4, 'Rene', 'Russ', 3);
insert into Child (child_id, first_name, last_name, age) values (5, 'Reta', 'Crowder', 2);
insert into Child (child_id, first_name, last_name, age) values (6, 'Virgie', 'Andrichuk', 3);
insert into Child (child_id, first_name, last_name, age) values (7, 'Hadlee', 'Pattie', 5);
insert into Child (child_id, first_name, last_name, age) values (8, 'Glori', 'Cawsby', 4);
insert into Child (child_id, first_name, last_name, age) values (9, 'Gabie', 'Yeliashev', 2);
insert into Child (child_id, first_name, last_name, age) values (10, 'Marysa', 'Flaune', 4);
insert into Child (child_id, first_name, last_name, age) values (11, 'Ekaterina', 'Mitchiner', 3);
insert into Child (child_id, first_name, last_name, age) values (12, 'Wilfrid', 'O''Heagertie', 1);
insert into Child (child_id, first_name, last_name, age) values (13, 'Vivyan', 'Gibling', 3);
insert into Child (child_id, first_name, last_name, age) values (14, 'Eddy', 'Woodberry', 4);
insert into Child (child_id, first_name, last_name, age) values (15, 'Phil', 'Milbourn', 5);
insert into Child (child_id, first_name, last_name, age) values (16, 'Julie', 'Erlam', 3);
insert into Child (child_id, first_name, last_name, age) values (17, 'Anselm', 'Sill', 1);
insert into Child (child_id, first_name, last_name, age) values (18, 'Bev', 'Spellecy', 1);
insert into Child (child_id, first_name, last_name, age) values (19, 'Faun', 'Earles', 2);
insert into Child (child_id, first_name, last_name, age) values (20, 'Correy', 'Tourner', 4);

/* Parent */
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (1, 'Douglass', 'Schild', '9721294291', 'dschild0@cpanel.net', 'mother', 6171);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (2, 'Elka', 'Gallimore', '6156083291', 'egallimore1@163.com', 'father', 10188);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (3, 'Ferdinande', 'Darrigone', '5283184803', 'fdarrigone2@mlb.com', 'mother', 6486);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (4, 'Burton', 'Cardozo', '7523463340', 'bcardozo3@amazonaws.com', 'mother', 9271);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (5, 'Cazzie', 'McNamee', '1725517728', 'cmcnamee4@ed.gov', 'mother', 5440);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (6, 'Maxine', 'Beldham', '3535104937', 'mbeldham5@dion.ne.jp', 'father', 8468);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (7, 'Anders', 'Whaley', '1203586355', 'awhaley6@arstechnica.com', 'mother', 3965);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (8, 'Lambert', 'Comrie', '2624124725', 'lcomrie7@amazon.de', 'father', 17004);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (9, 'Olivier', 'Goodison', '4205173823', 'ogoodison8@netvibes.com', 'father', 2867);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (10, 'Daniela', 'Short', '3244982329', 'dshort9@arstechnica.com', 'father', 2489);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (11, 'Vaughn', 'Cockley', '8283200062', 'vcockleya@desdev.cn', 'father', 7143);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (12, 'Myranda', 'Bowers', '6272034273', 'mbowersb@networksolutions.com', 'father', 14984);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (13, 'Dimitri', 'Gonsalvo', '9703709739', 'dgonsalvoc@washington.edu', 'mother', 6024);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (14, 'Basilio', 'Lacknor', '9423954218', 'blacknord@phoca.cz', 'father', 18010);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (15, 'Berna', 'Cherrie', '4306092319', 'bcherriee@disqus.com', 'mother', 7246);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (16, 'Tildy', 'Moncreif', '6428555585', 'tmoncreiff@hud.gov', 'father', 19324);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (17, 'Reta', 'Lumbly', '4043017332', 'rlumblyg@mysql.com', 'father', 9071);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (18, 'Johnny', 'Ilive', '7825988583', 'jiliveh@wufoo.com', 'father', 15345);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (19, 'Jasmin', 'Goodlett', '7824368174', 'jgoodletti@github.io', 'mother', 12300);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (20, 'Kirsteni', 'Doak', '7767226496', 'kdoakj@cnet.com', 'mother', 10696);
