alter table `#__mkv_projects` add `groupID` int unsigned not null after managerID;
alter table `#__mkv_projects` add index `#__mkv_projects_groupID_index` (groupID);
alter table `#__mkv_projects` add constraint `#__mkv_projects_#__usergroups_groupID_id_fk` foreign key (groupID) references `#__usergroups` (id) on delete cascade on update cascade;
alter table `#__mkv_thematics` drop index `#__mkv_thematics_published_index`;
alter table `#__mkv_thematics` drop published;
alter table `#__mkv_thematics` add weight smallint unsigned not null default 0 after for_ndp;
alter table `#__mkv_thematics` add index `#__mkv_thematics_weight_index` (weight);
