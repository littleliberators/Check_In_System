/* Delete the tables if they already exist */
drop table if exists Parent;
drop table if exists Child;
drop table if exists ParentHasChild;

/* Create the schema for our tables */
create table Child(child_id int, first_name varchar(50), last_name varchar(50), age int);
create table Parent(parent_id int, first_name varchar(50), last_name varchar(50), phone_num varchar(10), email varchar(50), relationship varchar(50), PIN int);
create table ParentHasChild(parent_id int, child_id int);

/* Populate the tables with our data */
/* Child */
insert into Child (child_id, first_name, last_name, age) values (1, 'Miguela', 'Schild', 0);
insert into Child (child_id, first_name, last_name, age) values (2, 'Maridel', 'Schild', 0);
insert into Child (child_id, first_name, last_name, age) values (3, 'Phaidra', 'Darrigone', 4);
insert into Child (child_id, first_name, last_name, age) values (4, 'Rene', 'Darrigone', 3);
insert into Child (child_id, first_name, last_name, age) values (5, 'Reta', 'Cardozo', 2);
insert into Child (child_id, first_name, last_name, age) values (6, 'Virgie', 'Beldham', 3);
insert into Child (child_id, first_name, last_name, age) values (7, 'Hadlee', 'Comrie', 5);
insert into Child (child_id, first_name, last_name, age) values (8, 'Glori', 'Comrie', 4);
insert into Child (child_id, first_name, last_name, age) values (9, 'Gabie', 'Comrie', 2);
insert into Child (child_id, first_name, last_name, age) values (10, 'Marysa', 'Comrie', 4);
insert into Child (child_id, first_name, last_name, age) values (11, 'Ekaterina', 'Goodison', 3);
insert into Child (child_id, first_name, last_name, age) values (12, 'Wilfrid', 'Short', 1);
insert into Child (child_id, first_name, last_name, age) values (13, 'Vivyan', 'Short', 3);
insert into Child (child_id, first_name, last_name, age) values (14, 'Eddy', 'Cockley', 4);
insert into Child (child_id, first_name, last_name, age) values (15, 'Phil', 'Cockley', 5);
insert into Child (child_id, first_name, last_name, age) values (16, 'Julie', 'Gonsalvo', 3);
insert into Child (child_id, first_name, last_name, age) values (17, 'Anselm', 'Lacknor', 1);
insert into Child (child_id, first_name, last_name, age) values (18, 'Bev', 'Moncreif', 1);
insert into Child (child_id, first_name, last_name, age) values (19, 'Faun', 'Ilive', 2);
insert into Child (child_id, first_name, last_name, age) values (20, 'Correy', 'Tourner', 4);

/* Parent */
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (1, 'Douglass', 'Schild', '9721294291', 'dschild0@cpanel.net', 'father', 6171);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (2, 'Elka', 'Schild', '6156083291', 'egallimore1@163.com', 'mother', 6171);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (3, 'Ferdinande', 'Darrigone', '5283184803', 'fdarrigone2@mlb.com', 'mother', 6486);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (4, 'Burton', 'Cardozo', '7523463340', 'bcardozo3@amazonaws.com', 'father', 9271);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (5, 'Cazzie', 'Beldham', '1725517728', 'cmcnamee4@ed.gov', 'mother', 5440);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (6, 'Max', 'Beldham', '3535104937', 'mbeldham5@dion.ne.jp', 'father', 5440);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (7, 'Anders', 'Comrie', '1203586355', 'awhaley6@arstechnica.com', 'mother', 3965);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (8, 'Lambert', 'Comrie', '2624124725', 'lcomrie7@amazon.de', 'father', 3965);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (9, 'Olivier', 'Goodison', '4205173823', 'ogoodison8@netvibes.com', 'father', 2867);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (10, 'Daniel', 'Short', '3244982329', 'dshort9@arstechnica.com', 'father', 2489);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (11, 'Venus', 'Cockley', '8283200062', 'vcockleya@desdev.cn', 'mother', 7143);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (12, 'Myranda', 'Gonsalvo', '6272034273', 'mbowersb@networksolutions.com', 'mother', 14984);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (13, 'Dimitri', 'Gonsalvo', '9703709739', 'dgonsalvoc@washington.edu', 'father', 14984);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (14, 'Basilio', 'Lacknor', '9423954218', 'blacknord@phoca.cz', 'father', 7246);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (15, 'Berna', 'Lacknor', '4306092319', 'bcherriee@disqus.com', 'mother', 7246);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (16, 'Tildy', 'Moncreif', '6428555585', 'tmoncreiff@hud.gov', 'father', 19324);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (17, 'Reta', 'Ilive', '4043017332', 'rlumblyg@mysql.com', 'mother', 9071);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (18, 'Johnny', 'Ilive', '7825988583', 'jiliveh@wufoo.com', 'father', 9071);
insert into Parent (parent_id, first_name, last_name, phone_num, email, relationship, PIN) values (19, 'Jasmin', 'Goodlett', '7824368174', 'jgoodletti@github.io', 'mother', 12300);

/* ParentHasChild */
insert into ParentHasChild (parent_id, child_id) values (1, 1);
insert into ParentHasChild (parent_id, child_id) values (1, 2);
insert into ParentHasChild (parent_id, child_id) values (2, 1);
insert into ParentHasChild (parent_id, child_id) values (2, 2);
insert into ParentHasChild (parent_id, child_id) values (3, 3);
insert into ParentHasChild (parent_id, child_id) values (3, 4);
insert into ParentHasChild (parent_id, child_id) values (4, 5);
insert into ParentHasChild (parent_id, child_id) values (5, 6);
insert into ParentHasChild (parent_id, child_id) values (6, 6);
insert into ParentHasChild (parent_id, child_id) values (7, 7);
insert into ParentHasChild (parent_id, child_id) values (7, 8);
insert into ParentHasChild (parent_id, child_id) values (7, 9);
insert into ParentHasChild (parent_id, child_id) values (7, 10);
insert into ParentHasChild (parent_id, child_id) values (8, 7);
insert into ParentHasChild (parent_id, child_id) values (8, 8);
insert into ParentHasChild (parent_id, child_id) values (8, 9);
insert into ParentHasChild (parent_id, child_id) values (8, 10);
insert into ParentHasChild (parent_id, child_id) values (9, 11);
insert into ParentHasChild (parent_id, child_id) values (10, 12);
insert into ParentHasChild (parent_id, child_id) values (10, 13);
insert into ParentHasChild (parent_id, child_id) values (11, 14);
insert into ParentHasChild (parent_id, child_id) values (11, 15);
insert into ParentHasChild (parent_id, child_id) values (12, 16);
insert into ParentHasChild (parent_id, child_id) values (13, 16);
insert into ParentHasChild (parent_id, child_id) values (14, 17);
insert into ParentHasChild (parent_id, child_id) values (15, 17);
insert into ParentHasChild (parent_id, child_id) values (16, 18);
insert into ParentHasChild (parent_id, child_id) values (17, 19);
insert into ParentHasChild (parent_id, child_id) values (18, 19);
insert into ParentHasChild (parent_id, child_id) values (19, 20);