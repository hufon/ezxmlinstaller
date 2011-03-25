<?php
//
// Created on: <2011-03-25 22:00:00 dis@isozaponol.de>
//
// SOFTWARE NAME: eZ XML Installer extension for eZ Publish
// SOFTWARE RELEASE: 0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//

include_once('extension/ezxmlinstaller/classes/ezxmlinstallerhandler.php');

class eZMoveContent extends eZXMLInstallerHandler
{

    function eZMoveContent( )
    {
    }

    function execute( $xmlNode )
    {
        $sourceNodeIdString = $xmlNode->getAttribute( 'sourceNode' );
        $targetParentNodeIdString = $xmlNode->getAttribute( 'targetParentNode' );

        $sourceNodeId = $this->getReferenceID( $sourceNodeIdString );
        $targetParentNodeId = $this->getReferenceID( $targetParentNodeIdString );

        if ( !$sourceNodeId )
        {
            $this->writeMessage( "\tInvalid source node $sourceNodeId does not exist.", 'warning' );
            return false;
        }
        if ( !$targetParentNodeId )
        {
            $this->writeMessage( "\tInvalid target node $targetParentNodeId does not exist.", 'warning' );
            return false;
        }

        $targetNode = eZContentObjectTreeNode::fetch( $targetParentNodeId );
        if ( !$targetNode )
        {
            $this->writeMessage( "\tTarget node does not exist.", 'error' );
            return false;
        }
        $node = eZContentObjectTreeNode::fetch( $sourceNodeId );
        if ( !$node )
        {
            $this->writeMessage( "\tSource node does not exist.", 'error' );
            return false;
        }
        $objectId = $node->attribute( 'contentobject_id' );

        if( !eZContentObjectTreeNodeOperations::move( $sourceNodeId, $targetParentNodeId ) )
        {
            $this->writeMessage( "Failed to move node $nodeID as child of parent node $newParentNodeID", 'error' );
            return false;
        }
        eZContentObject::fixReverseRelations( $objectId, 'move' );

    }

    static public function handlerInfo()
    {
        return array( 'XMLName' => 'MoveContent', 'Info' => 'move content' );
    }
}

?>
