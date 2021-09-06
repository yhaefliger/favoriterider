import Grid from '@components/grid/grid';
import FiltersResetExtension from "@components/grid/extension/filters-reset-extension";
import SortingExtension from "@components/grid/extension/sorting-extension";
import ReloadListExtension from "@components/grid/extension/reload-list-extension";
import ExportToSqlManagerExtension from "@components/grid/extension/export-to-sql-manager-extension";
import ReloadListActionExtension from '@components/grid/extension/reload-list-extension';


const $ = window.$;

$(() => {
  const ridersGrid = new Grid('rider_entity');

  ridersGrid.addExtension(new FiltersResetExtension());
  ridersGrid.addExtension(new SortingExtension());
  ridersGrid.addExtension(new ReloadListExtension());
  ridersGrid.addExtension(new ExportToSqlManagerExtension());
  ridersGrid.addExtension(new ReloadListActionExtension());
});