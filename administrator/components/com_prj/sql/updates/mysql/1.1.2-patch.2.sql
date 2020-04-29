alter table `#__mkv_projects`
    add catalogID smallint unsigned null default null after priceID,
    add foreign key `#__mkv_projects_#__mkv_stand_catalogs_catalogID_id_fk` (catalogID)
        references `#__mkv_stand_catalogs` (id)
        on update cascade on delete restrict;

