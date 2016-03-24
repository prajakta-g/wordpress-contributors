<?php
/*
 * Interface for implementing meta boxes
 * 
 */
interface Interface_Meta_Box {

    public function wpi_add_meta();

    public function wpi_display_meta($content);

    public function wpi_save_meta();
}
