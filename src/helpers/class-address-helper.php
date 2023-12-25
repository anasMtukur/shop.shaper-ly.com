<?php
namespace SRC\Helpers;

class AddressHelper {
	public static function getAddressFromJson( $addressString ){
		$addressJson = json_decode( $addressString );
		
		$address  = '';
		if( $addressJson->address_line1 !== '' ){
		    $address .= $addressJson->address_line1 . ', <br />';
		}
		if( $addressJson->address_line2 !== '' ){
			$address .= $addressJson->address_line2 . ', ';
		}
		if( $addressJson->city !== '' ){
		    $address .= $addressJson->city . ',<br /> ';
		}
		if( $addressJson->postcode !== '' ){
		    $address .= $addressJson->postcode . ', ';
		}
		if( $addressJson->state !== '' ){
		    $address .= $addressJson->state; // . ' State.'
		}

		return $address;
	}
}