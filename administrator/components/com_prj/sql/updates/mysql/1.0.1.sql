alter table s7vi9_mkv_projects add `groupID` int unsigned not null after managerID;

alter table s7vi9_mkv_projects add index s7vi9_mkv_projects_groupID_index (groupID);

alter table s7vi9_mkv_projects add constraint `s7vi9_mkv_projects_s7vi9_usergroups_groupID_id_fk` foreign key (groupID) references `s7vi9_usergroups` (id) on delete cascade on update cascade;
